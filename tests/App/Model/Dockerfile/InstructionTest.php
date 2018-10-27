<?php

namespace App\Tests\App\Model\Dockerfile;

use App\Model\Dockerfile\Image;
use App\Model\Dockerfile\Instruction;
use PHPUnit\Framework\TestCase;

class InstructionTest extends TestCase
{
    /**
     * @test
     * @throws \App\Exception\InvalidNameException
     */
    public function fromWithoutAlias()
    {
        $instruction = Instruction::from(Image::fromString('test-image:tag'));
        $this->assertEquals('FROM test-image:tag', (string)$instruction);
    }

    /**
     * @test
     * @throws \App\Exception\InvalidNameException
     */
    public function fromWithAlias()
    {
        $instruction = Instruction::from(Image::fromString('test-image:tag'), 'test-alias');
        $this->assertEquals('FROM test-image:tag AS test-alias', (string)$instruction);
    }

    /**
     * @test
     */
    public function runWithSingleCommand()
    {
        $instruction = Instruction::run('echo "single command"');
        $this->assertEquals('RUN echo "single command"', (string)$instruction);
    }

    /**
     * @test
     */
    public function runWithMultipleArguments()
    {
        $instruction = Instruction::run('echo "more"', 'echo " then "', 'echo "one"');
        $this->assertEquals('RUN echo "more" && echo " then " && echo "one"', (string)$instruction);
    }

    /**
     * @test
     */
    public function emptyLine()
    {
        $instruction = Instruction::emptyLine();
        $this->assertEquals('', (string)$instruction);
    }

    /**
     * @test
     */
    public function addWithSingleSource()
    {
        $instruction = Instruction::add('/some/source', '/tmp');
        $this->assertEquals('ADD ["/some/source", "/tmp"]', (string)$instruction);
    }

    /**
     * @test
     */
    public function addWithMultipleSources()
    {
        $instruction = Instruction::addMultipleSources([
            '/some/source/file1',
            '/some/source/file2',
            '/some/source/file3'
        ], '/tmp');
        $this->assertEquals('ADD ["/some/source/file1", "/some/source/file2", "/some/source/file3", "/tmp"]', (string)$instruction);
    }

    /**
     * @test
     */
    public function copyWithSingleSource()
    {
        $instruction = Instruction::copy('/some/source', '/tmp');
        $this->assertEquals('COPY ["/some/source", "/tmp"]', (string)$instruction);
    }

    /**
     * @test
     */
    public function copyWithMultipleSources()
    {
        $instruction = Instruction::copyMultipleSources([
            '/some/source/file1',
            '/some/source/file2',
            '/some/source/file3'
        ], '/tmp');
        $this->assertEquals('COPY ["/some/source/file1", "/some/source/file2", "/some/source/file3", "/tmp"]', (string)$instruction);
    }
}
