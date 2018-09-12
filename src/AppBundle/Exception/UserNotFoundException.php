<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/12/2018
 * Time: 12:16 PM
 */

namespace AppBundle\Exception;


use AppBundle\Entity\User;
use Throwable;

class UserNotFoundException extends \Exception implements NotFoundException
{

    public function __construct(string $message = "", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    function getEntityName(): string
    {
        $refl = new \ReflectionClass(User::class);
        return $refl->getShortName();
    }
}