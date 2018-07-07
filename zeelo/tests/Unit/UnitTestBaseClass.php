<?php

namespace App\Tests\Unit;

use App\Application\ItemUseCases;
use App\Domain\Entity\Item;
use App\Domain\Entity\ItemRepository;
use App\Domain\ValueObjects\Url;
use PHPUnit\Framework\TestCase;
use Faker\Factory as FakerFactory;

class UnitTestBaseClass extends TestCase
{
    /**
     * @var $faker Generates Random Values to be used on the tests
     */
    protected $faker;

    /**
     * @var $repositoryMock Isolate the tests from the repository on Infrastructure layer
     */
    protected $repositoryMock;

    public function setUp()
    {
        parent::setUp();
        $this->faker = FakerFactory::create();
    }

    /**
     * Helper used to create Item Entity Object.
     *
     * @param $url
     * @return Item
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    protected function createItem(
        int $id = null,
        string $image = null,
        string $title = null,
        string $author = null,
        float $price = null
    ) {
        if (is_null($image)) {
            $image = new Url($this->faker->url);
        } else {
            $image = new Url($image);
        }
        if (is_null($title)) {
            $title = $this->faker->catchPhrase;
        }
        if (is_null($author)) {
            $author = $this->faker->name;
        }
        if (is_null($price)) {
            $price = $this->faker->randomFloat(2, 10, 60);
        }

        return new Item($id, $image, $title, $author, $price);
    }

    /**
     * Helper used to create Url Object.
     *
     * @param $url
     * @return Url
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    protected function createUrl($url)
    {
        return new Url($url);
    }

    /**
     * @return ItemUseCases
     */
    protected function buildUseCaseObject()
    {
        $this->repositoryMock = \Mockery::mock(ItemRepository::class);

        return new ItemUseCases($this->repositoryMock);
    }
}