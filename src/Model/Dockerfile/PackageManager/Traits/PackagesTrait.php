<?php

namespace App\Model\Dockerfile\PackageManager\Traits;

trait PackagesTrait
{
    /**
     * @var string[]
     */
    private $packages = [];

    /**
     * @param string[] $packages
     */
    public function requirePackages(array $packages): void
    {
        foreach ($packages as $package) {
            if (!in_array($package, $this->packages)) {
                $this->packages[] = $package;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasPackages(): bool
    {
        return !empty($this->packages);
    }
}
