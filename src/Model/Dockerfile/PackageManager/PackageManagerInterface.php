<?php

namespace App\Model\Dockerfile\PackageManager;

use App\Model\Dockerfile\Instruction;

interface PackageManagerInterface
{
    /**
     * @param string[] $packages
     */
    public function requirePackages(array $packages): void;

    /**
     * @return bool
     */
    public function hasPackages(): bool;

    /**
     * @return string
     */
    public static function getIdentifier(): string;

    /**
     * @return Instruction
     */
    public function getInstruction(): Instruction;
}
