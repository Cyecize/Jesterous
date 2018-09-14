<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 3:40 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\Role;
use AppBundle\Exception\IllegalArgumentException;

interface IRoleDbManager
{

    function createRolesIfNotExist() : void ;

    /**
     * @param string $roleName
     * @return Role
     * @throws IllegalArgumentException
     */
    function createRole(string $roleName) : ?Role;

    /**
     * @param int $id
     * @return Role
     */
    function findById(int $id):  ?Role;

    /**
     * @param string $roleName
     * @return Role
     */
    function findByRoleName(string  $roleName) : ?Role;

    /**
     * @return Role[]
     */
    function findAll() : array ;

}