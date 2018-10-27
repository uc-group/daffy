<?php

namespace App\Tests\App\Model\Dockerfile;

use App\Exception\InvalidInstructionUsageException;
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

    /**
     * @test
     */
    public function cmd()
    {
        $instruction = Instruction::cmd('/usr/bin/wc', ['--help']);
        $this->assertEquals('CMD ["/usr/bin/wc", "--help"]', (string)$instruction);
    }

    /**
     * @test
     */
    public function singleLabel()
    {
        $instruction = Instruction::singleLabel('version', '1.0');
        $this->assertEquals('LABEL version="1.0"', (string)$instruction);
    }

    /**
     * @test
     */
    public function multiLabel()
    {
        $instruction = Instruction::multiLabel([
            'version' => '1.0',
            'multi.label1' => 'value1',
            'other' => 'value3'
        ]);
        $this->assertEquals('LABEL version="1.0" multi.label1="value1" other="value3"', (string)$instruction);
    }

    /**
     * @test
     */
    public function exposeTcp()
    {
        $instruction = Instruction::expose(80);
        $this->assertEquals('EXPOSE 80/tcp', (string)$instruction);
    }

    /**
     * @test
     */
    public function exposeUdp()
    {
        $instruction = Instruction::expose(80, Instruction::ARG_UDP);
        $this->assertEquals('EXPOSE 80/udp', (string)$instruction);
    }

    /**
     * @test
     */
    public function singleEnv()
    {
        $instruction = Instruction::singleEnv('name', 'John Doe');
        $this->assertEquals('ENV name="John Doe"', (string)$instruction);
    }

    /**
     * @test
     */
    public function multiEnv()
    {
        $instruction = Instruction::multiEnv([
            'name' => 'John Doe',
            'cat' => 'Oh Long Johnson'
        ]);
        $this->assertEquals('ENV name="John Doe" cat="Oh Long Johnson"', (string)$instruction);
    }

    /**
     * @test
     */
    public function entrypoint()
    {
        $instruction = Instruction::entrypoint('executable', ['param1', 'param2']);
        $this->assertEquals('ENTRYPOINT ["executable", "param1", "param2"]', (string)$instruction);
    }

    /**
     * @test
     */
    public function volume()
    {
        $instruction = Instruction::volume(['/var/log', '/var/db']);
        $this->assertEquals('VOLUME ["/var/log", "/var/db"]', (string)$instruction);
    }

    /**
     * @test
     */
    public function user()
    {
        $instruction = Instruction::user('john');
        $this->assertEquals('USER john', (string)$instruction);
    }

    /**
     * @test
     */
    public function userWithGroup()
    {
        $instruction = Instruction::user('john', 'group');
        $this->assertEquals('USER john:group', (string)$instruction);
    }

    /**
     * @test
     */
    public function workdir()
    {
        $instruction = Instruction::workdir('/tmp');
        $this->assertEquals('WORKDIR /tmp', (string)$instruction);
    }

    /**
     * @test
     */
    public function arg()
    {
        $instruction = Instruction::arg('APP_USER');
        $this->assertEquals('ARG APP_USER', (string)$instruction);
    }

    /**
     * @test
     */
    public function argWithDefault()
    {
        $instruction = Instruction::arg('APP_USER', 'johndoe');
        $this->assertEquals('ARG APP_USER=johndoe', (string)$instruction);
    }

    /**
     * @test
     */
    public function onbuildThrowsExceptionForOnbuildInstruction()
    {
        $this->expectException(InvalidInstructionUsageException::class);
        Instruction::onbuild(Instruction::onbuild(Instruction::run('echo "hello"')));
    }

    /**
     * @test
     */
    public function onbuildThrowsExceptionForFromInstruction()
    {
        $this->expectException(InvalidInstructionUsageException::class);
        Instruction::onbuild(Instruction::from(Image::fromString('test-image')));

    }

    /**
     * @test
     */
    public function onbuild()
    {
        $instruction = Instruction::onbuild(Instruction::run('echo "hello"'));
        $this->assertEquals('ONBUILD RUN echo "hello"', (string)$instruction);
    }

    /**
     * @test
     */
    public function stopsignal()
    {
        $instruction = Instruction::stopsignal(9);
        $this->assertEquals('STOPSIGNAL 9', (string)$instruction);
    }

    /**
     * @test
     */
    public function disableHealthcheck()
    {
        $instruction = Instruction::disableHealthCheck();
        $this->assertEquals('HEALTHCHECK NONE', (string)$instruction);
    }

    /**
     * @test
     */
    public function healthcheckThrowsExceptionOnOtherInstructionThenCmd()
    {
        $this->expectException(InvalidInstructionUsageException::class);
        Instruction::healthcheck(Instruction::run('mysqladmin', 'ping', '-h', 'localhost'));
    }

    /**
     * @test
     */
    public function healthcheck()
    {
        $instruction = Instruction::healthcheck(Instruction::cmd(
            'mysqladmin',
            ['ping', '-h', 'localhost']
        ), '30s', '30s', '0s', 3);
        $this->assertEquals('HEALTHCHECK --interval=30s --timeout=30s --start-period=0s --retries=3 CMD ["mysqladmin", "ping", "-h", "localhost"]', (string)$instruction);
    }

    /**
     * @test
     */
    public function is()
    {
        $instruction = Instruction::cmd('echo', ['"test"']);
        $this->assertTrue($instruction->is(Instruction::CMD));
    }
}
