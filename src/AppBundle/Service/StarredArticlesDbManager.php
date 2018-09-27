<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/27/2018
 * Time: 12:17 PM
 */

namespace AppBundle\Service;

use AppBundle\Contracts\IStarredArticlesDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;
use Doctrine\ORM\EntityManagerInterface;


class StarredArticlesDbManager implements IStarredArticlesDbManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $articleRepo;

    /**
     * StarredArticlesDbManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->articleRepo = $entityManager->getRepository(Article::class);
    }

    /**
     * @param Article $article
     * @param User $user
     */
    public function addStarredArticle(Article $article, User $user): void
    {
        $user->addStarredArticle($article);
        $this->save($user);
    }

    /**
     * @param Article $article
     * @param User $user
     */
    public function removeStarredArticle(Article $article, User $user): void
    {
        $user->removeStarredArticle($article);
        $this->save($user);
    }

    /**
     * @param User $user
     * @param Pageable $pageable
     * @return Page
     */
    public function findByUser(User $user, Pageable $pageable): Page
    {
        return $this->articleRepo->findStarredArticles($user, $pageable);
    }

    private function save($o){
        $this->entityManager->merge($o);
        $this->entityManager->flush();
    }
}