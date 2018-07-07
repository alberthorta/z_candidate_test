<?php

namespace App\DataFixtures;

use App\Application\ItemUseCases;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ItemFixture, used to load sample data to the database
 * @package App\DataFixtures
 */
class ItemFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function load(ObjectManager $manager)
    {
        $itemUseCases = new ItemUseCases($manager->getRepository('App\Domain\Entity\Item'));

        $initData = [
            [
                'https://images-na.ssl-images-amazon.com/images/I/51FUYfErOXL._SX408_BO1,204,203,200_.jpg',
                'Complete Code: A Practical Handbook of Software Contruction',
                'Steve McConnell',
                39.6
            ],
            [
                'https://images-na.ssl-images-amazon.com/images/I/515iEcDr1GL._SX385_BO1,204,203,200_.jpg',
                'Clean Code: A Handbook of Agile Software Craftmanship',
                'Robert C. Martin',
                40.55
            ],
            [
                'https://images-na.ssl-images-amazon.com/images/I/41BjtnvIUQL._SX382_BO1,204,203,200_.jpg',
                'Clean Architecture: A Craftman´s Guide to Software Structure and Design',
                'Robert C.Martin',
                33.78
            ],
            [
                'https://images-na.ssl-images-amazon.com/images/I/51kDbV%2BN65L._SX396_BO1,204,203,200_.jpg',
                'Test Driven Development. By Example',
                'Kent Beck',
                39.07
            ],
            [
                'https://images-na.ssl-images-amazon.com/images/I/51szD9HC9pL._SX395_BO1,204,203,200_.jpg',
                'Design Patterns: Elements of Reusable Object-Oriented Software',
                'Erich Gamma',
                48.69
            ],
            [
                'https://images-na.ssl-images-amazon.com/images/I/41BKx1AxQWL._SX396_BO1,204,203,200_.jpg',
                'The Pragmatic Programmer: From Journeyman to Master',
                'Andrew Hunt',
                36.33
            ],
            [
                'https://images-na.ssl-images-amazon.com/images/I/51WIpM70FEL._SX334_BO1,204,203,200_.jpg',
                'The Mythical Man Month: Essays on Software Engineering',
                'Frederick P. Brooks',
                35.37
            ],
            [
                'https://images-na.ssl-images-amazon.com/images/I/41gHB8KelXL._SX377_BO1,204,203,200_.jpg',
                'The C Programming Language',
                'Brian W. Kernighan & Dennis M. Ritchie',
                42.73
            ]
        ];
        foreach($initData as $book) {
            $itemUseCases->createItem(...$book);
        }
    }
}