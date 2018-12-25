<?php

namespace App\Tests\App\Model\DockerCompose;

use PHPUnit\Framework\TestCase;
use App\Model\DockerCompose\Service\BuildService;
use App\Model\DockerCompose\Service\ImageService;
use App\Model\DockerCompose\BaseFile;
use App\Model\DockerCompose\Service\Element\Volume;
use App\Model\DockerCompose\Service\Element\Port;
use Symfony\Component\Yaml\Yaml;

class DockerComposeTest extends TestCase
{
    /**
     * @var BaseFile
     */
    private $dockerComposeFile;

    public function setUp()
    {
        $this->dockerComposeFile = new BaseFile('3');
    }

    public function testVersion()
    {
        $this->assertEquals("version: '3'\n", $this->dockerComposeFile->toYaml());
    }

    public function testEmptyBuildService()
    {
        $someService = new BuildService('someService', './someBuild');
        $expectedResult = [
            'build' => './someBuild'
        ];
        $this->assertEquals($expectedResult, $someService->toArray());
    }

    public function testEmptyImageService()
    {
        $someService = new ImageService('someService', 'someImage');
        $expectedResult = [
            'image' => 'someImage'
        ];
        $this->assertEquals($expectedResult, $someService->toArray());
    }

    public function testFile()
    {
        $imageService = new ImageService('imageService', 'mysql:5.7');
        $imageService->setContainerName('image-service');
        $imageService->addDependsOn('buildService');
        $imageService->addDns('8.8.8.8');
        $imageService->addDns('1.1.1.1');
        $imageService->addVolume(new Volume('./src', '/var/www/app'));
        $imageService->addPort(new Port('3306', '33060'));

        $buildService = new BuildService('buildService', './apache');
        $buildService->addDns('8.8.8.8');
        $buildService->addVolume(new Volume('./src', '/app/src'));
        $buildService->addVolume(new Volume('./db', '/app/db'));
        $buildService->addVolume(new Volume('./additional', '/app/additional'));
        $buildService->addPort(new Port('80', '8080'));
        $buildService->addPort(new Port('90', '9090'));

        $this->dockerComposeFile->addService($imageService);
        $this->dockerComposeFile->addService($buildService);

        $version = Yaml::dump(['version' => '3'], BaseFile::INLINE_LEVEL, BaseFile::INDENT_SIZE);
        $services = Yaml::dump([
            'services' => [
                'imageService' => [
                    'image' => 'mysql:5.7',
                    'container_name' => 'image-service',
                    'ports' => [
                        '3306:33060'
                    ],
                    'depends_on' => [
                        'buildService'
                    ],
                    'volumes' => [
                        './src:/var/www/app'
                    ],
                    'dns' => [
                        '8.8.8.8',
                        '1.1.1.1'
                    ]
                ],
                'buildService' => [
                    'build' => './apache',
                    'ports' => [
                        '80:8080',
                        '90:9090'
                    ],
                    'volumes' => [
                        './src:/app/src',
                        './db:/app/db',
                        './additional:/app/additional'
                    ],
                    'dns' => [
                        '8.8.8.8'
                    ]
                ]
            ]
        ], BaseFile::INLINE_LEVEL, BaseFile::INDENT_SIZE);

        $this->assertEquals(
            $version . PHP_EOL . $services, 
            $this->dockerComposeFile->toYaml()
        );
    }
}
