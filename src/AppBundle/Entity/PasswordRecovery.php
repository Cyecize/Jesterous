<?php

namespace AppBundle\Entity;

use AppBundle\Constants\Config;
use Doctrine\ORM\Mapping as ORM;

/**
 * PasswordRecovery
 *
 * @ORM\Table(name="password_recovery")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PasswordRecoveryRepository")
 */
class PasswordRecovery
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
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_requested", type="datetime")
     */
    private $timeRequested;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     * @var User
     */
    private $user;

    public function __construct()
    {
        $this->timeRequested = new \DateTime('now', new \DateTimeZone(Config::DEFAULT_TIMEZONE));
        $this->setToken(md5(uniqid()));
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
     * Set token
     *
     * @param string $token
     *
     * @return PasswordRecovery
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set timeRequested
     *
     * @param \DateTime $timeRequested
     *
     * @return PasswordRecovery
     */
    public function setTimeRequested($timeRequested)
    {
        $this->timeRequested = $timeRequested;

        return $this;
    }

    /**
     * Get timeRequested
     *
     * @return \DateTime
     */
    public function getTimeRequested()
    {
        return $this->timeRequested;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}

