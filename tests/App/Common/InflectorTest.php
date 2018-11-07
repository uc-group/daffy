<?php

namespace App\Tests\App\Common;

use App\Common\Inflector;
use PHPUnit\Framework\TestCase;

class InflectorTest extends TestCase
{
    /**
     * @test
     */
    public function replacesMultipleSpacesToSingleHyphen()
    {
        $this->assertEquals('this_is_test', Inflector::toNormalizedHyphenCase('this       is   test'));
    }

    /**
     * @test
     */
    public function replacesDotsWithHyphen()
    {
        $this->assertEquals('this_is_test', Inflector::toNormalizedHyphenCase('this...is..test'));
    }

    /**
     * @test
     */
    public function convertsToLowerCase()
    {
        $this->assertEquals('this_is_test', Inflector::toNormalizedHyphenCase('This...IS tESt'));
    }

    /**
     * @test
     */
    public function removesNonStandardCharacters()
    {
        $this->assertEquals('this_is_test', Inflector::toNormalizedHyphenCase('Thisśąśðœ²³©æ„śąð is (*&^$%#$&%*()_ test'));
    }
}
