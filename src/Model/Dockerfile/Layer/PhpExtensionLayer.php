<?php

namespace App\Model\Dockerfile\Layer;

use App\Entity\OperatingSystem;
use App\Exception\CannotCreateLayerException;
use App\Model\Dockerfile\Instruction;

class PhpExtensionLayer implements LayerInterface, OperatingSystemAwareInterface
{
    private static $PACKAGES = [
        'mcrypt' => ['libmcrypt-dev'],
        'zmq' => ['libzmq3-dev'],
        'gd' => ['libfreetype6-dev', 'libjpeg62-turbo-dev', 'libpng-dev gnupg']
    ];

    private static $INSTALL_BY_PECL = [
        'xdebug' => 'xdebug-2.5.0',
        'zmq' => 'zmq-beta'
    ];

    private $extensions = [];

    /**
     * @return Instruction[]
     */
    public function getInstructions(): array
    {
        $peclCmd = $this->getPeclInstallCommands();
        $enableCmd = $this->getEnableCommands();
        $instructions = [
            Instruction::run(...$peclCmd, ...$enableCmd)
        ];

        if (in_array('xdebug', $this->extensions)) {
            $instructions[] = Instruction::copy('./config/xdebug.ini', '/usr/local/etc/php/xdebug.ini');
        }

        return $instructions;
    }

    /**
     * @param Definition $definition
     * @return LayerInterface
     * @throws CannotCreateLayerException
     */
    public static function create(Definition $definition): LayerInterface
    {
        $extensions = $definition->getArgument('extensions', false);
        if (!$extensions || empty($extensions)) {
            throw new CannotCreateLayerException('No extensions selected.');
        }

        $layer = new self();
        $layer->extensions = $extensions;

        return $layer;
    }

    public function supports(OperatingSystem $operatingSystem): bool
    {
        return preg_match('/^ubuntu-/', $operatingSystem->getId()->toString()) === 1;
    }

    /**
     * @param OperatingSystem $operatingSystem
     * @return array
     */
    public function getPackages(OperatingSystem $operatingSystem): array
    {
        $packages = [];
        foreach ($this->extensions as $extension) {
            if (key_exists($extension, self::$PACKAGES)) {
                foreach (self::$PACKAGES[$extension] as $package) {
                    $packages[] = $package;
                }
            }
        }

        return array_unique($packages);
    }

    public function getConfigFiles(OperatingSystem $operatingSystem): array
    {
        // TODO: Implement getConfigFiles() method.
        return [];
    }

    /**
     * @return array
     */
    private function getPeclInstallCommands()
    {
        $cmd = [];
        foreach ($this->extensions as $extension) {
            if (key_exists($extension, self::$INSTALL_BY_PECL)) {
                $cmd[] = sprintf('pecl install %s', self::$INSTALL_BY_PECL[$extension]);
            }
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
