<?php

namespace App\Entity;

use App\Model\OperatingSystem\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class OperatingSystem implements \JsonSerializable
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var Collection|ImageConfig[]
     * @ORM\OneToMany(targetEntity="ImageConfig", mappedBy="operatingSystem")
     */
    private $imageConfigs;

    /**
     * OperatingSystem constructor.
     * @param Id $id
     * @param string|null $description
     */
    public function __construct(Id $id, string $description = null)
    {
        $this->id = $id->toString();
        $this->description = $description;
        $this->imageConfigs = new ArrayCollection();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return Id::fromString($this->id);
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
     * @param ImageConfig $imageConfig
     */
    public function addImageConfig(ImageConfig $imageConfig)
    {
        if (!$this->imageConfigs->contains($imageConfig)) {
            $imageConfig->setOperatingSystem($this);
            $this->imageConfigs->add($imageConfig);
        }
    }

    /**
     * @param ImageConfig $imageConfig
     */
    public function removeImageConfig(ImageConfig $imageConfig)
    {
        $imageConfig->setOperatingSystem(null);
        $this->imageConfigs->removeElement($imageConfig);
    }

    /**
     * @param array $imageConfigs
     */
    public function updateImages(array $imageConfigs)
    {
        foreach ($this->imageConfigs as $config) {
            $config->setOperatingSystem(null);
        }

        $this->imageConfigs->clear();
        foreach ($imageConfigs as $imageConfig) {
            $this->addImageConfig($imageConfig);
        }
    }

    /**
     * @return string[]
     */
    public function getImages(): array
    {
        $images = [];
        foreach ($this->imageConfigs as $image) {
            $images[] = $image->getId();
        }

        return $images;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'images' => $this->getImages()
        ];
    }
}
