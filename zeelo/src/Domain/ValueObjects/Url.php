<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidUrlException;

/**
 * Url ValueObject (to be used by the entities and have some control over the bussines logic)
 * @package App\Domain\ValueObjects
 */
class Url
{
    private $url;

    /**
     * Url constructor.
     * @param string $url
     * @throws InvalidUrlException
     */
    public function __construct(string $url)
    {
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException();
        }
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->url;
    }
}