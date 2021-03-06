<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 10:57 AM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\BindingModel\UserInfoBindingModel;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;

interface IUserDbManager
{
    /**
     * @param User $user
     */
    function save(User $user) : void ;

    /**
     * @param User $user
     * @param UserInfoBindingModel $bindingModel
     */
    function editProfile(User $user, UserInfoBindingModel $bindingModel): void ;

    /**
     * @param User $user
     * @param Role $role
     * @throws IllegalArgumentException
     */
    function removeRole(User $user, Role $role) : void ;

    /**
     * @param User $user
     * @param Role $role
     * @throws IllegalArgumentException
     */

    function addRole(User $user, Role $role): void;

    /**
     * @param User $target
     * @param User $celeb
     */
    function addFollower(User $target, User $celeb): void;

    /**
     * @param User $target
     * @param User $celeb
     */

    function removeFollower(User $target, User $celeb): void;

    /**
     * @param User $user
     * @param ChangePasswordBindingModel $bindingModel
     * @param bool $verify
     */
    function changePassword(User $user, ChangePasswordBindingModel $bindingModel, bool $verify = true) : void ;

    /**
     * @param User $user
     * @param ImageBindingModel $bindingModel
     */
    function changeProfilePicture(User $user, ImageBindingModel $bindingModel) : void ;

    /**
     * @param User $user
     * @throws IllegalArgumentException
     */
    function removeAccount(User $user) : void ;

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
    function findByRole(string $role): array;
}