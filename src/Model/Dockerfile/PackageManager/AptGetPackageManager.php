<?php

namespace App\Model\Dockerfile\PackageManager;

use App\Model\Dockerfile\Instruction;
use App\Model\Dockerfile\PackageManager\Traits\PackagesTrait;

class AptGetPackageManager implements PackageManagerInterface
{
    use PackagesTrait;

    /**
     * @return string
     */
    public static function getIdentifier(): string
    {
        return 'Apt (Advanced Packaging Tool)';
    }

    /**
     * @return Instruction
     */
    public function getInstruction(): Instruction
    {
        return Instruction::run(
            'apt-get update',
            sprintf('DEBIAN_FRONTEND=noninteractive apt-get install -y %s', implode(' ', $this->packages)),
            'rm -r /var/lib/apt/lists/*');
    }
}
