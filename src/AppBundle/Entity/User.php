<?php

namespace AppBundle\Entity;

use AppBundle\Constants\Config;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_registered", type="datetime")
     */
    private $dateRegistered;

    /**
     * @var string
     *
     * @ORM\Column(name="user_description", type="text", nullable=true)
     */
    private $userDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_image", type="string", length=255, nullable=true)
     */
    private $profileImage;

    /**
     * @var string
     * @ORM\Column(name="nickname", type="string", length=100, nullable=true)
     */
    private $nickname;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role")
     * @ORM\JoinTable(name="roles_users", joinColumns={@ORM\JoinColumn(name="user_id", onDelete="CASCADE")}, inverseJoinColumns={@ORM\JoinColumn(name="role_id", onDelete="CASCADE")})
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="users_followers",
     *     joinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="followed_user_id", referencedColumnName="id", onDelete="CASCADE")})
     * @var User[]
     */
    private $following;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="following")
     * @var User[]
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Article", inversedBy="usersThatStarred")
     * @ORM\JoinTable(name="users_starred_articles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE")})
     * @var Article[]
     */
    private $starredArticles;

    public function __construct()
    {
        $this->dateRegistered = new \DateTime('now', new \DateTimeZone(Config::DEFAULT_TIMEZONE));
        $this->roles = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->starredArticles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     *
     * @return User
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * Set userDescription
     *
     * @param string $userDescription
     *
     * @return User
     */
    public function setUserDescription($userDescription)
    {
        $this->userDescription = $userDescription;

        return $this;
    }

    /**
     * Get userDescription
     *
     * @return string
     */
    public function getUserDescription()
    {
        return $this->userDescription;
    }

    /**
     * Set profileImage
     *
     * @param string $profileImage
     *
     * @return User
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * Get profileImage
     *
     * @return string
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @return string
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }


    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @param ArrayCollection $roles
     */
    public function setRoles(ArrayCollection $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {

    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    /**
     * @return User[]
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param User[] $following
     */
    public function setFollowing(array $following): void
    {
        $this->following = $following;
    }

    /**
     * @return User[]
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param User[] $followers
     */
    public function setFollowers($followers): void
    {
        $this->followers = $followers;
    }

    /**
     * @return Article[]
     */
    public function getStarredArticles()
    {
        return $this->starredArticles;
    }

    /**
     * @param Article[] $starredArticles
     */
    public function setStarredArticles(array $starredArticles): void
    {
        $this->starredArticles = $starredArticles;
    }

    public function addRole(Role $role)
    {
        if (!$this->roles->contains($role))
            $this->roles->add($role);
    }

    public function removeRole(Role $role)
    {
        if ($this->roles->contains($role))
            $this->roles->removeElement($role);
    }

    public function hasRole(string $role): bool
    {
        foreach ($this->roles as $userRole) {
            if ($userRole->getRole() == $role)
                return true;
        }
        return false;
    }

    public function follow(User $user)
    {
        $this->following->add($user);
    }

    public function unfollow(User $celeb)
    {
        $this->following->removeElement($celeb);
    }

    public function isFollowing(User $celeb): bool
    {
        return $this->following->contains($celeb);
    }

    public function addStarredArticle(Article $article)
    {
        if (!$this->starredArticles->contains($article))
            $this->starredArticles->add($article);
    }

    public function removeStarredArticle(Article $article)
    {
        if ($this->starredArticles->contains($article))
            $this->starredArticles->removeElement($article);
    }

    public function hasUserStarred(Article $article) : bool {
        return $this->starredArticles->contains($article);
    }
}

