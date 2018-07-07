<?php

namespace App\Domain\Entity;

use App\Domain\ValueObjects\Url;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Item
 * @package App\Domain\Entity
 * @ORM\Entity
 * @ORM\Table(name="item")
 * @Serializer\ExclusionPolicy("none")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\ItemRepositoryDoctrine")
 */
class Item
{
    /**
     * @todo: Warning, this const seems to be too coupled to our API url system, we should evaluate a way to fix this problem. Move this constant to a configuration value would be an option.
     */
    const API_ROUTE_LINK_PREFIX = "/api/v1/items/";

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"entityFields","itemList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Serializer\Groups({"entityFields"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=200)
     * @Serializer\Groups({"entityFields","itemList"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=200)
     * @Serializer\Groups({"entityFields"})
     */
    private $author;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"entityFields"})
     */
    private $price;

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\Groups({"itemList"})
     */
    public function getLink()
    {
        return static::API_ROUTE_LINK_PREFIX.$this->id;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->getId()===null;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Item constructor.
     * @param int|null $id
     * @param string $image
     * @param string $title
     * @param string $author
     * @param float $price
     */
    public function __construct(?int $id, Url $image, string $title, string $author, float $price)
    {
        $this->id = $id;
        $this->image = $image;
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
    }
}