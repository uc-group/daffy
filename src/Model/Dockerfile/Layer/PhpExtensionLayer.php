<?php

namespace App\Model\Dockerfile\Layer;

use App\Entity\OperatingSystem;
use App\Exception\CannotCreateLayerException;
use App\Model\Dockerfile\Instruction;

class PhpExtensionLayer implements LayerInterface, OperatingSystemAwareInterface
{
    /**
     * @var string[]
     */
    private $extensions;

    /**
     * @var string[]
     */
    private $peclPackages;
    /**
     * @var string[]
     */
    private $osPackages;

    /**
     * @var array
     */
    private $files = [];

    /**
     * PhpExtensionLayer constructor.
     * @param array $extensions
     * @param array $peclPackages
     * @param array $osPackages
     */
    public function __construct(array $extensions, array $peclPackages, array $osPackages)
    {
        $this->extensions = $extensions;
        $this->peclPackages = $peclPackages;
        $this->osPackages = $osPackages;
    }

    /**
     * @param string $name
     * @param string $destination
     * @param string $content
     */
    public function addFile(string $name, string $destination, string $content)
    {
        $this->files[] = [
            'source' => sprintf('./config/php/%s', $name),
            'destination' => $destination,
            'content' => $content
        ];
    }

    /**
     * @return Instruction[]
     */
    public function getInstructions(): array
    {
        if (empty($this->extensions)) {
            return [];
        }

        $peclCmd = $this->getPeclInstallCommands();
        $enableCmd = $this->getEnableCommands();
        $instructions = [
            Instruction::run(...$peclCmd, ...$enableCmd)
        ];

        foreach ($this->files as $file) {
            $instructions[] = Instruction::copy($file['source'], $file['destination']);
        }

        return $instructions;
    }

    /**
     * @return array
     */
    public function getPackages(): array
    {
        return $this->osPackages;
    }

    /**
     * @return array
     */
    public function getConfigFiles(): array
    {
        return $this->files;
    }

    /**
     * @return array
     */
    private function getPeclInstallCommands()
    {
        $cmd = [];
        foreach ($this->peclPackages as $package) {
            $cmd[] = sprintf('pecl install %s', $package);
        }

        return $cmd;
    }

    /**
     * @return array
     */
    private function getEnableCommands()
    {
        $cmd = [];
        foreach ($this->extensions as $extension) {
            $cmd[] = sprintf('docker-php-enable %s', $extension);
        }

        return $cmd;
    }
}
