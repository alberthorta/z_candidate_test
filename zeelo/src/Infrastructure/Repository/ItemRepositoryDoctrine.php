<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Item;
use App\Domain\Entity\ItemRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;

class ItemRepositoryDoctrine extends ServiceEntityRepository implements ItemRepository
{
    /**
     * ItemRepositoryDoctrine constructor.
     * @param ManagerRegistry $registry
     * @param string $entityClass
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, 'App\Domain\Entity\Item');
    }

    /**
     * @param Item $item
     * @return Item
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Item $item): Item
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return $item;
    }

    /**
     * @param int $item_id
     * @return Item|null
     */
    public function search(int $item_id): ?Item
    {
        return $this->find($item_id);
    }

    public function all(int $offset, int $count): array
    {
        return $this->findBy([], ['id' => 'ASC'], $count, $offset);
    }
}