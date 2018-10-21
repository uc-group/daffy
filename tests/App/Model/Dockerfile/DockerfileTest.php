<?php

namespace App\Tests\App\Model\Dockerfile;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Dockerfile;
use App\Model\Dockerfile\Image;
use App\Model\Dockerfile\Instruction;
use PHPUnit\Framework\TestCase;

class DockerfileTest extends TestCase
{
    /**
     * @var Dockerfile
     */
    private $instance;

    /**
     * @throws InvalidNameException
     */
    public function setUp()
    {
        $this->instance = new Dockerfile(Image::fromString('test-image:tag1'));
    }
    /**
     * @test
     */
    public function addsInstruction()
    {
        $this->instance->addInstruction(Instruction::run('apt update', 'apt install git'));
        $expected = <<<DF
FROM test-image:tag1

RUN apt update && apt install git

DF;

        $this->assertEquals($expected, (string)$this->instance);
    }
}
