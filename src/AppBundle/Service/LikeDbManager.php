<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 8:17 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\ILikeDbManager;
use AppBundle\Entity\LikeReaction;
use Doctrine\ORM\EntityManagerInterface;

class LikeDbManager implements ILikeDbManager
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $likeRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->quoteRepo = $em->getRepository(LikeReaction::class);
    }

    function createLike(LikeReaction $likeReaction): LikeReaction
    {
        $this->entityManager->persist($likeReaction);
        $this->entityManager->flush();
        return $likeReaction;
    }

    function findOneById(int $id)
    {
        return $this->likeRepo->findOneBy(array('id'=>$id));
    }

    function remove(LikeReaction $likeReaction)
    {
        $this->entityManager->remove($likeReaction);
        $this->entityManager->flush();
    }
}