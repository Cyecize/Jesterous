<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 10:57 AM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\User;

interface IUserDbManager
{
    /**
     * @param User $target
     * @param User $celeb
     */
    function addFollower(User $target, User $celeb) : void ;

    /**
     * @param User $target
     * @param User $celeb
     */
    function removeFollower(User $target, User $celeb) : void ;
    /**
     * @param User $candidate
     * @param User $celebrity
     * @return bool
     */
    function isUserFollowing(User $candidate, User $celebrity): bool;

    /**
     * @param int $id
     * @return User
     */
    function findOneById(int $id): ?User;

    /**
     * @param string $username
     * @return User
     */
    function findOneByUsername(string $username): ?User;

    /**
     * @return User[]
     */
    function findAll(): array;

    /**
     * @param string $role
     * @return User[]
     */
    function findByRole(string  $role) : array ;
}