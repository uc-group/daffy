<?php

namespace App\Model\OperatingSystem;

use App\Common\Inflector;

class Id
{
    /**
     * @var string
     */
    private $value;

    /**
     * Id constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $name
     * @param string $version
     * @return Id
     */
    public static function fromNameAndVersion(string $name, string $version): self
    {
        return new self(Inflector::toNormalizedHyphenCase($name) . '_' . Inflector::toNormalizedHyphenCase($version));
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
