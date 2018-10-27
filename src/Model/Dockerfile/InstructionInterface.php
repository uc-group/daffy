<?php

namespace App\Model\Dockerfile;

interface InstructionInterface
{
    /**
     * Tells if this instruction is a $name instruction
     *
     * @param string $name
     * @return bool
     */
    public function is(string $name): bool;

    /**
     * Returns instruction name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Builds instruction for dockerfile
     *
     * @return string
     */
    public function __toString(): string;
}
