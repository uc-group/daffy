<?php

namespace App\Entity;

use App\Model\Dockerfile\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ImageConfig
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @var OperatingSystem|null
     * @ORM\ManyToOne(targetEntity="OperatingSystem", inversedBy="imageConfigs")
     * @ORM\JoinColumn(referencedColumnName="id", name="operating_system_id", onDelete="SET NULL")
     */
    private $operatingSystem;

    /**
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->id = (string)$image;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return OperatingSystem
     */
    public function getOperatingSystem(): OperatingSystem
    {
        return $this->operatingSystem;
    }

    /**
     * @param OperatingSystem|null $operatingSystem
     */
    public function setOperatingSystem(?OperatingSystem $operatingSystem): void
    {
        $this->operatingSystem = $operatingSystem;
    }
}
