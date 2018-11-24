<?php

namespace App\Builder;

use App\Entity\DockerfileConfig;
use App\Entity\OperatingSystem;
use App\Exception\CannotCreateLayerException;
use App\Model\Dockerfile\Dockerfile;
use App\Model\Dockerfile\Image;
use App\Model\Dockerfile\Layer\Definition;
use App\Model\Dockerfile\Layer\InstructionLayer;
use App\Model\Dockerfile\Layer\LayerInterface;
use App\Model\Dockerfile\Layer\OperatingSystemAwareInterface;
use App\Model\Dockerfile\Layer\PhpExtensionLayer;
use App\Model\Dockerfile\PackageManager\PackageManagerInterface;
use App\Model\Dockerfile\PackageManager\PackageManagerRegistry;
use App\Repository\OperatingSystemRepository;
use Doctrine\ORM\EntityManagerInterface;

class DockerfileBuilder
{
    /**
     * @var PackageManagerRegistry
     */
    private $packageManagerRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string[]
     */
    private static $LAYER_MAP = [
        'instruction' => InstructionLayer::class,
        'php-extension' => PhpExtensionLayer::class
    ];

    /**
     * DockerfileBuilder constructor.
     * @param PackageManagerRegistry $packageManagerRegistry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PackageManagerRegistry $packageManagerRegistry, EntityManagerInterface $entityManager)
    {
        $this->packageManagerRegistry = $packageManagerRegistry;
        $this->entityManager = $entityManager;
    }

    /**
     * @param DockerfileConfig $config
     * @return Dockerfile
     * @throws \Exception
     */
    public function build(DockerfileConfig $config): Dockerfile
    {
        $dockerfile = new Dockerfile($config->getBaseImage());

        $operatingSystem = $this->getOperatingSystem($config->getBaseImage());
        $packageManager = $this->getPackageManager($operatingSystem);

        $layers = [];
        foreach ($config->getLayersDefinition() as $definition) {

            try {
                $layer = $this->buildLayer($definition, $operatingSystem);
            } catch (CannotCreateLayerException $exception) {
                continue;
            }

            if ($layer instanceof OperatingSystemAwareInterface) {
                $packageManager->requirePackages($layer->getPackages($operatingSystem));
            }

            $layers[] = $layer;
        }

        if ($packageManager->hasPackages()) {
            $dockerfile->addInstruction($packageManager->getInstruction());
        }

        $dockerfile->addEmptyLine();

        foreach ($layers as $layer) {
            foreach ($layer->getInstructions() as $instruction) {
                $dockerfile->addInstruction($instruction);
            }
            $dockerfile->addEmptyLine();
        }

        return $dockerfile;
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
     * @throws \Exception
     */
    private function buildLayer(Definition $definition, OperatingSystem $operatingSystem) {
        if (!key_exists($definition->getType(), self::$LAYER_MAP)) {
            throw new \Exception(sprintf('Unknown layer type "%s"', $definition->getType()));
        }

        $layer = call_user_func([self::$LAYER_MAP[$definition->getType()], 'create'], $definition);

        if ($layer instanceof OperatingSystemAwareInterface) {
            if (!$layer->supports($operatingSystem)) {
                throw new \Exception(sprintf(
                    'Layer "%s" is not supported by "%s" operating system.',
                    get_class($layer),
                    $operatingSystem->getId()->toString()
                ));
            }
        }

        return $layer;
    }
}
