<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 10:59 AM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\User;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use Doctrine\ORM\EntityManagerInterface;

class UserDbManager implements IUserDbManager
{
    private const USER_ALREADY_FOLLOWED = "User already followed";
    private const USER_ALREADY_UNFOLLOWED = "User already unfollowed";
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $userRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->userRepo = $em->getRepository(User::class);
    }

    /**
     * @param User $target
     * @param User $celeb
     * @throws RestFriendlyExceptionImpl
     */
    public function addFollower(User $target, User $celeb): void
    {
        if($this->isUserFollowing($target, $celeb))
            throw new RestFriendlyExceptionImpl(self::USER_ALREADY_FOLLOWED);
        $target->follow($celeb);
        $this->entityManager->merge($target);
        $this->entityManager->flush();
    }

    /**
     * @param User $target
     * @param User $celeb
     * @throws RestFriendlyExceptionImpl
     */
    public function removeFollower(User $target, User $celeb): void
    {
       if(!$this->isUserFollowing($target, $celeb))
           throw new RestFriendlyExceptionImpl(self::USER_ALREADY_UNFOLLOWED);
       $target->unfollow($celeb);
       $this->entityManager->merge($target);
       $this->entityManager->flush();
    }

    /**
     * @param User $candidate
     * @param User $celebrity
     * @return bool
     */
    public function isUserFollowing(User $candidate, User $celebrity): bool
    {
        foreach ($celebrity->getFollowers() as $follower) {
            if($follower->getId() == $candidate->getId())
                return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return User
     */
    function findOneById(int $id): ?User
    {
        return $this->userRepo->findOneBy(array('id'=>$id));
    }

    /**
     * @param string $username
     * @return User
     */
    function findOneByUsername(string $username): ?User
    {
        return $this->userRepo->findOneBy(array('username'=>$username));
    }

    /**
     * @return User[]
     */
    function findAll(): array
    {
        return $this->userRepo->findAll();
    }
}