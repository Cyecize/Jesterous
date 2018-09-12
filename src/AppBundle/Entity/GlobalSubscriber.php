<?php

namespace AppBundle\Entity;

use AppBundle\Constants\Config;
use Doctrine\ORM\Mapping as ORM;

/**
 * GlobalSubscriber
 *
 * @ORM\Table(name="global_subscribers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GlobalSubscriberRepository")
 */
class GlobalSubscriber
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
     * @ORM\Column(name="email", type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_subscribed", type="boolean")
     */
    private $isSubscribed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_subbed", type="datetime")
     */
    private $dateSubbed;


    public function __construct()
    {
        $this->dateSubbed = new \DateTime('now', new \DateTimeZone(Config::DEFAULT_TIMEZONE));
        $this->isSubscribed = true;
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
     * Set email
     *
     * @param string $email
     *
     * @return GlobalSubscriber
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
     * Set isSubscribed
     *
     * @param boolean $isSubscribed
     *
     * @return GlobalSubscriber
     */
    public function setIsSubscribed($isSubscribed)
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    /**
     * Get isSubscribed
     *
     * @return bool
     */
    public function getIsSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * Set dateSubbed
     *
     * @param \DateTime $dateSubbed
     *
     * @return GlobalSubscriber
     */
    public function setDateSubbed($dateSubbed)
    {
        $this->dateSubbed = $dateSubbed;

        return $this;
    }

    /**
     * Get dateSubbed
     *
     * @return \DateTime
     */
    public function getDateSubbed()
    {
        return $this->dateSubbed;
    }
}

