<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOperatingSystem extends PostOperatingSystem
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * CreateOperatingSystem constructor.
     * @param string $name
     * @param string $version
     * @param string $packageManager
     * @param string|null $description
     * @param array $images
     */
    protected function __construct(
        string $name,
        string $version,
        string $packageManager,
        string $description = null,
        array $images)
    {
        parent::__construct($packageManager, $description, $images);
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * @param Request $request
     * @return CreateOperatingSystem
     */
    public static function fromRequest(Request $request): self
    {
        $content = json_decode($request->getContent(), true);

        return new self(
            $content['name'] ?? '',
            $content['version'] ?? '',
            $content['packageManager'] ?? '',
            $content['description'] ?? null,
            $content['images'] ?? []
        );
    }

    /**
     * @return string
     * @Assert\NotBlank()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     * @Assert\NotBlank()
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
