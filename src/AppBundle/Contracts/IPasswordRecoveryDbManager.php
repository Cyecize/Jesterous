<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/27/2018
 * Time: 10:55 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\PasswordRecovery;
use AppBundle\Entity\User;

interface IPasswordRecoveryDbManager
{
    /**
     * @param PasswordRecovery $passwordRecovery
     */
    public function removePasswordRecovery(PasswordRecovery $passwordRecovery): void;

    /**
     * @param User $user
     * @return PasswordRecovery
     */
    public function addPasswordRecovery(User $user): PasswordRecovery;

    /**
     * @param int $id
     * @return PasswordRecovery|null
     */
    public function findOneById(int $id): ?PasswordRecovery;

    /**
     * @param string $token
     * @return PasswordRecovery|null
     */
    public function findOneByToken(string $token): ?PasswordRecovery;

    /**
     * @param User $user
     * @return PasswordRecovery|null
     */
    public function findOneByUser(User $user): ?PasswordRecovery;
}