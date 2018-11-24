<?php

namespace App\Request;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDockerfileConfig
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Image
     */
    private $baseImage;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string $name
     * @param Image $baseImage
     * @param string|null $description
     */
    private function __construct(Image $baseImage, string $name, string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->baseImage = $baseImage;
    }

    /**
     * @return string
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return Image
     * @Assert\NotNull()
     */
    public function getBaseImage(): Image
    {
        return $this->baseImage;
    }

    /**
     * @param Request $request
     * @return CreateDockerfileConfig
     * @throws InvalidNameException
     */
    public static function fromRequest(Request $request): self
    {
        $content = json_decode($request->getContent(), true);

        return new self(
            Image::fromString($content['baseImage'] ?? ''),
            $content['name'] ?? '',
            $content['description'] ?? null
        );
    }
}
