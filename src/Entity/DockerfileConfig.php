<?php

namespace App\Entity;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;
use App\Model\Dockerfile\Layer\Definition;
use App\Model\Dockerfile\Layer\LayerInterface;
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
     * @var Image
     * @ORM\Column(type="text")
     */
    private $baseImage;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    private $layersDefinition = [];

    /**
     * DockerfileConfig constructor.
     * @param Image $baseImage
     * @param string $name
     * @param string|null $description
     * @throws \Exception
     */
    public function __construct(Image $baseImage, string $name, string $description = null)
    {
        $this->id = Uuid::uuid4();
        $this->setBaseImage($baseImage);
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @param array $config
     */
    public function setLayersDefinition(array $config)
    {
        $this->layersDefinition = $config;
    }

    /**
     * @param Image $baseImage
     */
    public function setBaseImage(Image $baseImage)
    {
        $this->baseImage = (string)$baseImage;
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
     * @return Image
     * @throws InvalidNameException
     */
    public function getBaseImage(): Image
    {
        return Image::fromString($this->baseImage);
    }

    /**
     * @return Definition[]
     */
    public function getLayersDefinition(): array
    {
        //TODO: create doctrine type
        $definitions = [];
        foreach ($this->layersDefinition as $definition) {
            $definitions[] = Definition::fromArray($definition);
        }

        return $definitions;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'baseImage' => $this->baseImage,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
