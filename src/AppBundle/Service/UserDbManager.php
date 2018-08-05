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
use Doctrine\ORM\EntityManagerInterface;

class UserDbManager implements IUserDbManager
{
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