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
     * @param int $id
     * @return User
     */
    function findOneById(int $id) : ?User;

    /**
     * @param string $username
     * @return User
     */
    function findOneByUsername(string $username): ?User;

    /**
     * @return User[]
     */
    function findAll() : array ;
}