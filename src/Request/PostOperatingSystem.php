<?php

namespace App\Request;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;
use Symfony\Component\Validator\Constraints as Assert;

abstract class PostOperatingSystem
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $packageManager;

    /**
     * @var Image[]
     */
    private $images;

    /**
     * @param string $packageManager
     * @param string|null $description
     * @param string[] $images
     */
    protected function __construct(string $packageManager, string $description = null, array $images = [])
    {
        $this->packageManager = $packageManager;
        $this->description = $description;
        $this->images = [];

        foreach ($images as $image) {
            try {
                $image = Image::fromString($image);
                $this->images[] = $image;
            } catch (InvalidNameException $exception) {
                // Ignore invalid names
            }
        }
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return string
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public function getPackageManager(): string
    {
        return $this->packageManager;
    }
}
