<?php

namespace App\Tests\Unit;

use App\Application\ItemUseCases;
use App\Domain\ValueObjects\Url;
use PHPUnit\Framework\TestCase;
use Faker\Factory as FakerFactory;
use App\Domain\Entity\ItemRepository;
use App\Domain\Entity\Item;

/**
 * Class ItemUseCasesTest
 * @package App\Tests
 */
class ItemUseCasesTest extends UnitTestBaseClass
{
    /**
     * @var $userCaseInstance is an instance of ItemUserCase object
     */
    private $userCaseInstance;

    /**
     * setUp method
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = FakerFactory::create();
        $this->userCaseInstance = $this->buildUseCaseObject();
    }

    /**
     * Test that the ItemCreation on Application layer is trying to create the Item
     */
    public function testItemCreation()
    {
        $url   = $this->faker->url;
        $title = $this->faker->title;
        $name  = $this->faker->name;
        $price = $this->faker->randomFloat(2,10,60);

        $mockItem = \Mockery::mock(Item::class);
        $mockItem->allows()->asArray()->andReturns([
            "id" => $this->faker->randomNumber(),
            "image" => $url,
            "title" => $name,
            "price" => $price
        ]);
        $this->repositoryMock->shouldReceive('save')->with(\Mockery::type(Item::class))->andReturn($mockItem);

        $item = $this->userCaseInstance->createItem($url, $title, $name, $price);

        $this->assertInternalType('array', $item);
    }

    /**
     * Test that the ItemListing on Application layer is trying to get the list of items
     */
    public function testListItems()
    {
        $offset = $this->faker->randomNumber();
        $count  = $this->faker->randomNumber();

        $this->repositoryMock->shouldReceive('all')->with($offset, $count)->andReturn([]);

        $items = $this->userCaseInstance->listItems($offset, $count);

        $this->assertInternalType('array', $items);
    }

    /**
     * Test that GettingItem on Application layer is trying to access the requested item
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function testGetItem()
    {
        $id = $this->faker->randomNumber();
        $item = $this->createItem($id);

        $this->repositoryMock->shouldReceive('search')->with($id)->andReturn($item);

        $item = $this->userCaseInstance->getItem($id);

        $this->assertInternalType('array', $item);
        $this->assertTrue($item['id']!==0);
    }
}