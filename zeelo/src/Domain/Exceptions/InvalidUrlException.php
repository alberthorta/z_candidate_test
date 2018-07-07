<?php

namespace App\Domain\Exceptions;

use Throwable;

/**
 * Class InvalidUrlException
 * @package App\Domain\Exceptions
 */
class InvalidUrlException extends \Exception
{
    /**
     * InvalidUrlException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid URL provided", $code, $previous);
    }
}