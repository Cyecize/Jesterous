<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/27/2018
 * Time: 12:10 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;

interface IStarredArticlesDbManager
{
    /**
     * @param Article $article
     * @param User $user
     */
    public function addStarredArticle(Article $article, User $user): void ;

    /**
     * @param Article $article
     * @param User $user
     */
    public function removeStarredArticle(Article $article, User $user) : void ;

    /**
     * @param User $user
     * @param Pageable $pageable
     * @return Page
     */
    public function findByUser(User $user, Pageable $pageable) : Page;
}