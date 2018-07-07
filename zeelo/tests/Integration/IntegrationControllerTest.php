<?php

namespace App\Tests;

use App\Application\ItemUseCases;
use App\Domain\Exceptions\InvalidUrlException;
use App\Domain\ValueObjects\Url;
use Doctrine\ORM\EntityManager;
use JMS\SerializerBundle\JMSSerializerBundle;
use PHPUnit\Framework\TestCase;
use Faker\Factory as FakerFactory;
use App\Domain\Entity\ItemRepository;
use App\Domain\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ItemUseCasesTest
 * @package App\Tests
 */
class ItemIntegrationTest extends KernelTestCase
{
    /**
     * @var $faker Generates Random Values to be used on the tests
     */
    private $faker;

    /**
     * @var $repository , we're gonna use some real stuff this is an integration test
     */
    private $repository;

    /**
     * @var $userCaseInstance ItemUseCases to test
     */
    private $userCaseInstance;

    /**
     * ItemIntegrationTest constructor.
     * @param null|string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();

        $this->repository = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Item::class);
        $this->userCaseInstance = new ItemUseCases($this->repository);
    }

    /**
     * setUp method
     */
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
    private function createItem(
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
     * We can create items and store them on the DB
     * @throws InvalidUrlException
     */
    public function testItemCreation()
    {
        $url   = $this->faker->url;
        $title = $this->faker->catchPhrase;
        $name  = $this->faker->name;
        $price = $this->faker->randomFloat(2,10,60);

        $elements_before_insert = count($this->userCaseInstance->listItems(0, PHP_INT_MAX));

        $item = $this->userCaseInstance->createItem($url, $title, $name, $price);

        $elements_after_insert = count($this->userCaseInstance->listItems(0, PHP_INT_MAX));

        $this->assertInstanceOf(Item::class, $item);
        $this->assertGreaterThan($elements_before_insert, $elements_after_insert);
    }

    /**
     * We can't create items if the URL is not valid
     * @throws InvalidUrlException
     */
    public function testItemCreationWithInvalidUrl()
    {
        $url   = $this->faker->randomNumber();
        $title = $this->faker->catchPhrase;
        $name  = $this->faker->name;
        $price = $this->faker->randomFloat(2,10,60);

        $elements_before_insert = count($this->userCaseInstance->listItems(0, PHP_INT_MAX));

        $this->expectException(InvalidUrlException::class);

        $item = $this->userCaseInstance->createItem($url, $title, $name, $price);

        $elements_after_insert = count($this->userCaseInstance->listItems(0, PHP_INT_MAX));

        $this->assertInstanceOf(Item::class, $item);
        $this->assertEquals($elements_before_insert, $elements_after_insert);
    }

    /**
     * We can list the items on the DB
     */
    public function testItemListing()
    {
        $items = $this->userCaseInstance->listItems(0, PHP_INT_MAX);

        $this->assertInternalType('array', $items);

        foreach($items as $item) {
            $this->assertInstanceOf(Item::class, $item);
        }
    }

    /**
     * We can retrieve one of the items of the DB
     * @throws InvalidUrlException
     */
    public function testGetItem()
    {
        $url   = $this->faker->url;
        $title = $this->faker->catchPhrase;
        $name  = $this->faker->name;
        $price = $this->faker->randomFloat(2,10,60);

        $item = $this->userCaseInstance->createItem($url, $title, $name, $price);

        $item_retrieved = $this->userCaseInstance->getItem($item->getId());

        $this->assertEquals($item, $item_retrieved);

        $this->assertFalse($item->isNew());
    }
}