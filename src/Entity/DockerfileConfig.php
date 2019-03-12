<?php

namespace App\Entity;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;
use App\Model\Dockerfile\Layer\Definition;
use App\Model\Dockerfile\Layer\LayerInterface;
use App\Model\Dockerfile\Stage\ImageId;
use App\Model\Dockerfile\Stage\Stage;
use App\Model\Dockerfile\Stage\StageId;
use App\Model\Dockerfile\Stage\StageIdInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DockerfileConfigRepository")
 */
class DockerfileConfig implements \JsonSerializable
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $stages = [];

    /**
     * DockerfileConfig constructor.
     * @param Image $baseImage
     * @param string $name
     * @param string|null $description
     * @throws \Exception
     */
    public function __construct(Image $baseImage, string $name, string $alias = null, string $description = null)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->description = $description;
        $this->addImageStage((string)$baseImage, $alias);
    }

    /**
     * @return array
     */
    public function getStageAliases()
    {
        $stages = [];
        foreach ($this->stages as $stage) {
            $stages[$stage['index']] = $stage['alias'];
        }
        ksort($stages);

        return $stages;
    }

    /**
     * @param array $config
     */
    public function setLayersDefinition(array $config, string $stage = null)
    {
        if ($stage === null) {
            $aliases = $this->getStageAliases();
            if (empty($aliases)) {
                return;
            }
            $this->stages[$aliases[0]]['layers'] = $config;
        } else if (isset($this->stages[$stage])) {
            $this->stages[$stage]['layers'] = $config;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getStages(): array
    {
        $aliases = $this->getStageAliases();
        $stages = [];
        foreach ($aliases as $alias) {
            $stages[] = $this->stages[$alias];
        }

        return $stages;
    }

    /**
     * @param string $stage
     * @param string $alias
     */
    public function addStageStage(string $stage, string $alias): void
    {
        $this->stages[$alias] = [
            'stage' => $stage,
            'alias' => $alias,
            'index' => count($this->stages),
            'layers' => []
        ];
    }

    /**
     * @param string $image
     * @param string $alias
     */
    public function addImageStage(string $image, string $alias): void
    {
        $this->stages[$alias] = [
            'image' => $image,
            'alias' => $alias,
            'index' => count($this->stages),
            'layers' => []
        ];
    }

    /**
     * @param string|null $stage
     * @return Definition[]
     */
    public function getLayersDefinition(string $stage = null): array
    {
        if ($stage === null) {
            $aliases = $this->getStageAliases();
            if (empty($aliases)) {
                return [];
            }

            $layers = $this->stages[$aliases[0]]['layers'];
        } else if (isset($this->stages[$stage])) {
            $layers = $this->stages[$stage]['layers'];
        } else {
            $layers = [];
        }

        $definitions = [];
        foreach ($layers as $layer) {
            $definitions[] = Definition::fromArray($layer);
        }

        return $definitions;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $aliases = $this->getStageAliases();

        return [
            'id' => $this->id,
            'baseImage' => isset($aliases[0]) ? $this->stages[$aliases[0]]['image'] : null,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
