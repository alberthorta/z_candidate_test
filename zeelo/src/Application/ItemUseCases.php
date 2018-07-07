<?php

/**
 * Item Use Cases
 */

namespace App\Application;

use App\Domain\Entity\Item;
use App\Domain\Entity\ItemRepository;
use App\Domain\ValueObjects\Url;

/**
 * Class ItemUseCases
 * @package App\Application
 */
class ItemUseCases
{
    /**
     * @var ItemRepository
     */
    private $repository;

    /**
     * ItemUseCases constructor.
     * @param ItemRepository $repository
     */
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $image
     * @param string $title
     * @param string $author
     * @param float $price
     * @return Item
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function createItem(string $image, string $title, string $author, float $price)
    {
        return $this->repository->save(
            new Item(
                null,
                new Url($image),
                $title,
                $author,
                $price
            )
        );
    }

    /**
     * @param int $offset
     * @param int $count
     * @return array
     */
    public function listItems(int $offset, int $count): array
    {
        return $this->repository->all($offset, $count);
    }

    /**
     * @param int $item_id
     * @return Item|null
     */
    public function getItem(int $item_id): ?Item
    {
        return $this->repository->search($item_id);
    }
}