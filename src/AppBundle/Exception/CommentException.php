<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 9:43 AM
 */

namespace AppBundle\Exception;


use Throwable;

class CommentException extends \Exception implements InternalServerException
{
    public function __construct(string $message = "", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}