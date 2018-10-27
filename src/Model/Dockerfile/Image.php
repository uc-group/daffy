<?php

namespace App\Model\Dockerfile;

use App\Exception\InvalidNameException;

class Image
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $tag;

    /**
     * @param string $name
     * @param string $tag
     */
    private function __construct(string $name, string $tag)
    {
        $this->name = $name;
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s:%s', $this->name, $this->tag);
    }

    /**
     * @param string $imageName
     * @return Image
     * @throws InvalidNameException
     */
    public static function fromString(string $imageName): self
    {
        $elements = explode(':', $imageName, 2);

        if ($elements === false || strlen($imageName) === 0) {
            throw new InvalidNameException($imageName);
        }

        $name = $elements[0];
        $tag = (count($elements) === 1 ? 'latest' : $elements[1]);

        return new self($name, $tag);
    }
}
