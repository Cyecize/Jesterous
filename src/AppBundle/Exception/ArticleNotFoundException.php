<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/6/2018
 * Time: 5:10 PM
 */

namespace AppBundle\Exception;


use AppBundle\Entity\Article;
use Throwable;

class ArticleNotFoundException extends \Exception implements NotFoundException
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
        $refl = new \ReflectionClass(Article::class);
        return $refl->getShortName();

    }

}