<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/6/2018
 * Time: 1:28 AM
 */

namespace AppBundle\Exception;


use Throwable;

class RestFriendlyExceptionImpl extends \Exception implements RestFriendlyException
{
    public function __construct(string $message = "", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}