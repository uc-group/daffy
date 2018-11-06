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
     */
    protected function __construct(string $name, string $version, string $description = null, array $images)
    {
        parent::__construct($description, $images);
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
