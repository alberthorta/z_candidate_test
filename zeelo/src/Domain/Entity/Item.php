<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Item
 * @package App\Domain\Entity
 * @ORM\Entity
 * @ORM\Table(name="item")
 */
class Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    public $image;

    /**
     * @ORM\Column(type="string", length=200)
     */
    public $title;

    /**
     * @ORM\Column(type="string", length=200)
     */
    public $author;

    /**
     * @ORM\Column(type="float")
     */
    public $price;

    //Getters and Setters
}