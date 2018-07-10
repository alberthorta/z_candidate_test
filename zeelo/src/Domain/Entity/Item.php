<?php

namespace App\Domain\Entity;

use App\Domain\ValueObjects\Url;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Item
 * @package App\Domain\Entity
 */
class Item
{
    /**
     * Item ID
     * @var int|null
     */
    private $id;

    /**
     * URL of an image of the item
     * @var Url
     */
    private $image;

    /**
     * Title of the item
     * @var string
     */
    private $title;

    /**
     * Author of the item
     * @var string
     */
    private $author;

    /**
     * Price of the item
     * @var float
     */
    private $price;

    /**
     * Returns an Array representation of the items on the entity
     * @return array
     *
     * @note: Could have been implemented ArrayAccess interface but I think not necessary in this case
     */
    public function asArray()
    {
        return [
            'id' => $this->id,
            'image' => (string) $this->image,
            'title' => $this->title,
            'author' => $this->author,
            'price' => $this->price,
        ];
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return ((object) $this->asArray())->id === null;
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