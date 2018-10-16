<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 10:59 AM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\BindingModel\UserInfoBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Constants\Roles;
use AppBundle\Contracts\IFileManager;
use AppBundle\Contracts\ILogDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Util\ModelMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDbManager implements IUserDbManager
{
    private const LOGGER_LOCATION = "User Db Manager";
    protected const LOGGER_REMOVE_ACCOUNT_MESSAGE_FORMAT = "User %s is removing his account, directory %s should be gone!";

    private const USER_ALREADY_FOLLOWED = "User already followed";
    private const USER_ALREADY_UNFOLLOWED = "User already unfollowed";
    private const CANNOT_ALTER_ADMIN = "Cannot remove admin privileges!";
    private const USER_HAS_THAT_ROLE = "User already has that role!";
    private const USER_DOES_NOT_HAVE_ROLE = "User doesn't have that role!";

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $userRepo;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var IFileManager
     */
    private $fileService;

    /**
     * @var ILogDbManager
     */
    private $logService;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, IFileManager $fileManager, ILogDbManager $logDb, ModelMapper $modelMapper)
    {
        $this->entityManager = $em;
        $this->userRepo = $em->getRepository(User::class);
        $this->passwordEncoder = $passwordEncoder;
        $this->fileService = $fileManager;
        $this->logService = $logDb;
        $this->modelMapper = $modelMapper;
    }

    function save(User $user): void
    {
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    function editProfile(User $user, UserInfoBindingModel $bindingModel): void
    {
        $user = $this->modelMapper->merge($bindingModel, $user, true);
        $this->save($user);
    }

    function removeRole(User $user, Role $role): void
    {
        if ($user->hasRole(Roles::ROLE_ADMIN) && $role->getRole() == Roles::ROLE_ADMIN)
            throw new IllegalArgumentException(self::CANNOT_ALTER_ADMIN);
        if (!$user->hasRole($role->getRole()))
            throw new IllegalArgumentException(self::USER_DOES_NOT_HAVE_ROLE);
        $user->removeRole($role);
        $this->save($user);
    }

    function addRole(User $user, Role $role): void
    {
        if ($user->hasRole($role->getRole()))
            throw new IllegalArgumentException(self::USER_HAS_THAT_ROLE);
        $user->addRole($role);
        $this->save($user);
    }

    function addFollower(User $target, User $celeb): void
    {
        if ($this->isUserFollowing($target, $celeb))
            throw new RestFriendlyExceptionImpl(self::USER_ALREADY_FOLLOWED);
        $target->follow($celeb);
        $this->entityManager->merge($target);
        $this->entityManager->flush();
    }

    function removeFollower(User $target, User $celeb): void
    {
        if (!$this->isUserFollowing($target, $celeb))
            throw new RestFriendlyExceptionImpl(self::USER_ALREADY_UNFOLLOWED);
        $target->unfollow($celeb);
        $this->entityManager->merge($target);
        $this->entityManager->flush();
    }

    function changePassword(User $user, ChangePasswordBindingModel $bindingModel, bool $verify = true): void
    {
        if ($verify && !password_verify($bindingModel->getOldPassword(), $user->getPassword()))
            throw new IllegalArgumentException("passwordIsIncorrect");
        $user->setPassword($this->passwordEncoder->encodePassword($user, $bindingModel->getNewPassword()));
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    function changeProfilePicture(User $user, ImageBindingModel $bindingModel): void
    {
        if ($user->getProfileImage() != null)
            $this->fileService->removeFile(substr($user->getProfileImage(), 1));
        $user->setProfileImage($this->fileService->uploadFileToUser($bindingModel->getFile(), $user));
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    function removeAccount(User $user): void
    {
        $dir = sprintf(Config::USER_FILES_PATH_FORMAT, $user->getUsername());
        $this->logService->log(self::LOGGER_LOCATION, sprintf(self::LOGGER_REMOVE_ACCOUNT_MESSAGE_FORMAT, $user->getUsername(), $dir));
        $this->fileService->removeDirectory($dir);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    function isUserFollowing(User $candidate, User $celebrity): bool
    {
        foreach ($celebrity->getFollowers() as $follower) {
            if ($follower->getId() == $candidate->getId())
                return true;
        }
        return false;
    }

    function findOneById(int $id): ?User
    {
        return $this->userRepo->findOneBy(array('id' => $id));
    }

    function findOneByUsername(string $username): ?User
    {
        return $this->userRepo->findOneBy(array('username' => $username));
    }

    function findAll(): array
    {
        return $this->userRepo->findAll();
    }

    function findByRole(string $role): array
    {
        return $this->userRepo->findByRoleName($role);
    }

}