<?php

namespace App\Builder;

use App\Entity\DockerfileConfig;
use App\Entity\OperatingSystem;
use App\Exception\CannotCreateLayerException;
use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Dockerfile;
use App\Model\Dockerfile\Image;
use App\Model\Dockerfile\Instruction;
use App\Model\Dockerfile\Layer\Definition;
use App\Model\Dockerfile\Layer\InstructionLayer;
use App\Model\Dockerfile\Layer\LayerInterface;
use App\Model\Dockerfile\Layer\OperatingSystemAwareInterface;
use App\Model\Dockerfile\Layer\PhpExtensionLayer;
use App\Model\Dockerfile\LayerBuilder\LayerBuilderInterface;
use App\Model\Dockerfile\LayerBuilder\LayerBuilderRegistry;
use App\Model\Dockerfile\PackageManager\PackageManagerInterface;
use App\Model\Dockerfile\PackageManager\PackageManagerRegistry;
use App\Model\Dockerfile\Stage\ImageId;
use App\Model\Dockerfile\Stage\Stage;
use App\Model\Dockerfile\Stage\StageId;
use App\Repository\OperatingSystemRepository;
use Doctrine\ORM\EntityManagerInterface;

class DockerfileBuilder
{
    /**
     * @var string[]
     */
    private static $LAYER_MAP = [
        'instruction' => InstructionLayer::class,
        'php-extension' => PhpExtensionLayer::class
    ];

    /**
     * @var PackageManagerRegistry
     */
    private $packageManagerRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LayerBuilderRegistry
     */
    private $layerBuilderRegistry;

    /**
     * DockerfileBuilder constructor.
     * @param PackageManagerRegistry $packageManagerRegistry
     * @param LayerBuilderRegistry $layerBuilderRegistry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        PackageManagerRegistry $packageManagerRegistry,
        LayerBuilderRegistry $layerBuilderRegistry,
        EntityManagerInterface $entityManager
    ) {
        $this->packageManagerRegistry = $packageManagerRegistry;
        $this->entityManager = $entityManager;
        $this->layerBuilderRegistry = $layerBuilderRegistry;
    }

    /**
     * @param DockerfileConfig $config
     * @return Dockerfile
     * @throws \Exception
     */
    public function build(DockerfileConfig $config): Dockerfile
    {
        $dockerfile = new Dockerfile();

        $stages = $this->buildStages($config->getStages());
        foreach ($stages as $stage) {
            $dockerfile->addStage($stage);
            $operatingSystem = $this->getOperatingSystem($stage->getBaseImage());
            $packageManager = $this->getPackageManager($operatingSystem);

            $layers = [];
            foreach ($config->getLayersDefinition($stage->getAlias()) as $definition) {
                $layerBuilder = $this->layerBuilderRegistry->getBuilder($definition);
                if (!$layerBuilder instanceof LayerBuilderInterface) {
                    continue;
                }

                try {
                    $layer = $layerBuilder->build($definition, $operatingSystem);
                } catch (CannotCreateLayerException $exception) {
                    continue;
                }

                if ($layer instanceof OperatingSystemAwareInterface) {
                    $packageManager->requirePackages($layer->getPackages());
                }

                $layers[] = $layer;
            }

            if ($packageManager->hasPackages()) {
                $stage->addInstruction($packageManager->getInstruction());
            }

            foreach ($layers as $layer) {
                $stage->addLayer($layer);
            }
            $stage->addInstruction(Instruction::emptyLine());
        }

        return $dockerfile;
    }

    /**
     * @param $stagesConfig
     * @return Stage[]
     * @throws InvalidNameException
     * @throws \Exception
     */
    private function buildStages($stagesConfig): array
    {
        $resolvedStages = [];
        foreach ($stagesConfig as $i => $stage) {
            $alias = $stage['alias'];
            if (isset($stage['image'])) {
                $image = Image::fromString($stage['image']);
                $resolvedStages[$alias] = new Stage(new ImageId($image, $alias));
                unset($stagesConfig[$i]);
            }
        }

        $unresolved = [];
        do {
            foreach ($stagesConfig as $i => $stage) {
                $parentStage = $stage['stage'];
                $alias = $stage['alias'];
                if (!isset($resolvedStages[$parentStage])) {
                    if (isset($unresolved[$parentStage])) {
                        throw new \Exception(sprintf('Circular reference (%s -> %s)', $alias, $parentStage));
                    }

                    $unresolved[$alias] = true;
                } else {
                    if (isset($unresolved[$alias])) {
                        unset($unresolved[$alias]);
                    }

                    $resolvedStages[$alias] = new Stage(new StageId($resolvedStages[$parentStage], $alias));
                    unset($stagesConfig[$i]);
                }
            }
        } while(!empty($stagesConfig));

        return $resolvedStages;
    }

    /**
     * @param Image $image
     * @return OperatingSystem|null
     */
    private function getOperatingSystem(Image $image)
    {
        /** @var OperatingSystemRepository $repository */
        $repository = $this->entityManager->getRepository(OperatingSystem::class);

        return $repository->findOneByImage($image);
    }

    /**
     * @param OperatingSystem $operatingSystem
     * @return PackageManagerInterface
     * @throws \Exception
     */
    private function getPackageManager(OperatingSystem $operatingSystem): PackageManagerInterface
    {
        if (!$this->packageManagerRegistry->has($operatingSystem->getPackageManager())) {
            throw new \Exception(sprintf('Unknown package manager "%s"', $operatingSystem->getPackageManager()));
        }

        return $this->packageManagerRegistry->get($operatingSystem->getPackageManager());
    }

    /**
     * @param Definition $definition
     * @param OperatingSystem $operatingSystem
     * @return LayerInterface
     * @throws CannotCreateLayerException
     */
    private function buildLayer(Definition $definition, OperatingSystem $operatingSystem) {
        if (!key_exists($definition->getType(), self::$LAYER_MAP)) {
            throw new CannotCreateLayerException(sprintf('Unknown layer type "%s"', $definition->getType()));
        }

        $layer = call_user_func([self::$LAYER_MAP[$definition->getType()], 'create'], $definition);

        if ($layer instanceof OperatingSystemAwareInterface) {
            if (!$layer->supports($operatingSystem)) {
                throw new CannotCreateLayerException(sprintf(
                    'Layer "%s" is not supported by "%s" operating system.',
                    get_class($layer),
                    $operatingSystem->getId()->toString()
                ));
            }
        }

        return $layer;
    }
}
