<?php

namespace App\Tests\App\Model\Dockerfile;

use App\Exception\InvalidNameException;
use App\Model\Dockerfile\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @test
     * @throws \App\Exception\InvalidNameException
     */
    public function hasLatestTagIfNotSpecified()
    {
        $image = Image::fromString('test-image');
        $this->assertEquals('test-image:latest', (string)$image);
    }

    /**
     * @test
     * @throws \App\Exception\InvalidNameException
     */
    public function createsByNameAndTag()
    {
        $image = Image::fromString('test-image:tag1');
        $this->assertEquals('test-image:tag1', (string)$image);
    }

    /**
     * @test
     * @throws \App\Exception\InvalidNameException
     */
    public function throwsInvalidNameExceptionOnEmptyName()
    {
        $this->expectException(InvalidNameException::class);
        Image::fromString('');
    }
}
