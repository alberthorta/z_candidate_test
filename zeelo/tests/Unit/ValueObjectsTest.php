<?php

namespace App\Tests\Unit;

use App\Domain\ValueObjects\Url;
use PHPUnit\Framework\TestCase;
use Faker\Factory as FakerFactory;

/**
 * Class ValueObjectsTest
 * @package App\Tests
 */
class ValueObjectsTest extends UnitTestBaseClass
{
    /**
     * Test if Value Object can store a valid URL
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function testCreatesAValidUrl()
    {
        $url = $this->createUrl($this->faker->url);
        $this->assertInstanceOf('App\Domain\ValueObjects\Url', $url);
    }

    /**
     * Test that Value Object should not be able to store non valid URL's
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function testDoesNotCreateUrlObjectWhenUrlIsNotCorrect()
    {
        $this->expectException('App\Domain\Exceptions\InvalidUrlException');
        $this->createUrl($this->faker->name);
    }

    /**
     * Test that Value Object is actually storing the value.
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function testUrlCanBeAccessedAsAString()
    {
        $url_string = $this->faker->url;
        $url = $this->createUrl($url_string);
        $this->assertEquals((string) $url, $url_string);
    }
}