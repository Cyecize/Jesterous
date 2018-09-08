<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/8/2018
 * Time: 8:41 AM
 */

namespace AppBundle\Exception;


use AppBundle\Entity\Tag;
use AppBundle\Exception\NotFoundException;
use Throwable;

class TagNotFoundException extends \Exception implements NotFoundException
{
    public function __construct(string $message = "", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    function getEntityName(): string
    {
        $refl = new \ReflectionClass(Tag::class);
        return $refl->getShortName();
    }
}