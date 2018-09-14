<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 3:42 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Roles;
use AppBundle\Contracts\IRoleDbManager;
use AppBundle\Entity\Role;
use AppBundle\Exception\IllegalArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class RoleDbManager implements IRoleDbManager
{
    private const ROLE_NAME_TAKEN = "Role with that name already exists!";

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\RoleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $roleRepo;

    /**
     * RoleDbManager constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->roleRepo = $entityManager->getRepository(Role::class);
    }


    function createRolesIfNotExist(): void
    {
        try {
            if ($this->findByRoleName(Roles::ROLE_USER) == null)
                $this->createRole(Roles::ROLE_USER);
            if ($this->findByRoleName(Roles::ROLE_AUTHOR) == null)
                $this->createRole(Roles::ROLE_AUTHOR);
            if ($this->findByRoleName(Roles::ROLE_ADMIN) == null)
                $this->createRole(Roles::ROLE_ADMIN);
            if ($this->findByRoleName(Roles::ROLE_MAILER) == null)
                $this->createRole(Roles::ROLE_MAILER);
        } catch (IllegalArgumentException $e) {
        }
    }

    /**
     * @param string $roleName
     * @return Role
     * @throws IllegalArgumentException
     */
    function createRole(string $roleName): ?Role
    {
        if ($this->findByRoleName($roleName) != null)
            throw new IllegalArgumentException(self::ROLE_NAME_TAKEN);
        $role = new Role($roleName);
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        return $role;
    }

    /**
     * @param int $id
     * @return Role
     */
    function findById(int $id): ?Role
    {
        return $this->roleRepo->findOneBy(array('id' => $id));
    }

    /**
     * @param string $roleName
     * @return Role
     */
    function findByRoleName(string $roleName): ?Role
    {
        return $this->roleRepo->findOneBy(array('role' => $roleName));
    }

    /**
     * @return Role[]
     */
    function findAll(): array
    {
        return $this->roleRepo->findAll();
    }
}