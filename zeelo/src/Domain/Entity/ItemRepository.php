<?php

namespace App\Domain\Entity;

interface ItemRepository
{
    public function save(Item $item): Item;

    public function search(int $item_id): ?Item;

    public function all(int $offset, int $count): array;
}