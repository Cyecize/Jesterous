<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/4/2018
 * Time: 7:00 PM
 */

namespace AppBundle\Exception;


use Throwable;

class IllegalArgumentException extends \Exception implements InternalServerException
{
    public function __construct(string $message = "", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}