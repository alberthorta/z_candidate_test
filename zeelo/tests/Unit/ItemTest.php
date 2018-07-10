<?php

namespace App\Tests\Unit;

use App\Domain\Entity\Item;
use App\Domain\ValueObjects\Url;
use PHPUnit\Framework\TestCase;
use Faker\Factory as FakerFactory;

/**
 * Class ItemTest
 * @package App\Tests
 */
class ItemTest extends UnitTestBaseClass
{
    /**
     * Test the Item object creation
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function testCreatesAValidItem()
    {
        $item = $this->createItem();
        $this->assertInstanceOf('App\Domain\Entity\Item', $item);
    }

    /**
     * Test if the isNew method works properly on new items
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function testItemWithoutIdShouldBeConsideredNew()
    {
        $item = $this->createItem();
        $this->assertTrue($item->isNew());
    }

    /**
     * Test if the isNew method works properly on not new items
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function testItemWithIdShouldNotBeConsideredNew()
    {
        $item = $this->createItem($this->faker->randomNumber());
        $this->assertFalse($item->isNew());
    }
}