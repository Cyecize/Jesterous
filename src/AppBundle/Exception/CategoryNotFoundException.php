<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/5/2018
 * Time: 12:27 AM
 */

namespace AppBundle\Exception;


use AppBundle\Entity\ArticleCategory;
use Throwable;

class CategoryNotFoundException extends \Exception implements NotFoundException
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
       $refl = new \ReflectionClass(ArticleCategory::class);
      return $refl->getShortName();

    }
}