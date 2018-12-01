<?php

namespace App\Model\Dockerfile\LayerBuilder;

use App\Entity\OperatingSystem;
use App\Model\Dockerfile\Layer\Definition;
use App\Model\Dockerfile\Layer\LayerInterface;
use App\Model\Dockerfile\Layer\PhpExtensionLayer;
use Symfony\Component\Yaml\Yaml;
use App\Common\Inflector;

class PhpExtensionLayerBuilder implements LayerBuilderInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param string $configPath
     */
    public function __construct(string $configPath)
    {
        $this->config = Yaml::parse(file_get_contents($configPath));
    }

    public static function supports(Definition $definition)
    {
        return $definition->getType() === 'php-extension';
    }

    /**
     * @param Definition $definition
     * @param OperatingSystem $operatingSystem
     * @return LayerInterface
     */
    public function build(Definition $definition, OperatingSystem $operatingSystem): LayerInterface
    {
        $name = Inflector::toNormalizedUnderscoreCase($operatingSystem->getName());
        $version = Inflector::toNormalizedUnderscoreCase($operatingSystem->getVersion());
        $extensions = $definition->getArgument('extensions', []);
        $peclPackages = $this->getPeclPackages($name, $version, $extensions);
        $osPackages = $this->getOsPackages($name, $version, $extensions);

        return new PhpExtensionLayer($extensions, $peclPackages, $osPackages);
    }

    /**
     * @param string $name
     * @param string $version
     * @param array $extensions
     * @return array
     */
    private function getOsPackages(string $name, string $version, array $extensions): array
    {
        $chosenExtensions = array_fill_keys($extensions, null);
        $packages = $this->getPackages('os_packages', $name, $version);
        $packages = array_intersect_key($packages, $chosenExtensions);

        $result = [];
        foreach ($packages as $extensionPackages) {
            foreach ($extensionPackages as $package) {
                $result[] = $package;
            }
        }

        return $result;
    }

    /**
     * @param string $name
     * @param string $version
     * @param array $extensions
     * @return array
     */
    private function getPeclPackages(string $name, string $version, array $extensions): array
    {
        $chosenExtensions = array_fill_keys($extensions, null);
        $packages = $this->getPackages('pecl_packages', $name, $version);

        return array_values(array_intersect_key($packages, $chosenExtensions));
    }

    /**
     * @param string $collection
     * @param string $name
     * @param string $version
     * @return array
     */
    private function getPackages(string $collection, string $name, string $version): array
    {
        if (!isset($this->config[$collection][$name])) {
            return [];
        }

        $packages = $this->config[$collection][$name]['_default'] ?? [];
        $versionPackages = $this->config[$collection][$name][$version] ?? [];
        foreach ($versionPackages as $extension => $extensionPackages) {
            if ($extensionPackages === false) {
                if (isset($packages[$extension])) {
                    unset($packages[$extension]);
                }
            } else {
                $packages[$extension] = $extensionPackages;
            }
        }

        return $packages;
    }
}
