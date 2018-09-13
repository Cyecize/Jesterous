<?php

namespace AppBundle\Entity;

use AppBundle\Constants\Config;
use Doctrine\ORM\Mapping as ORM;

/**
 * LikeReaction
 *
 * @ORM\Table(name="likes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LikeReactionRepository")
 */
class LikeReaction
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_liked", type="datetime")
     */
    private $dateLiked;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    public function __construct()
    {
        $this->dateLiked = new \DateTime('now', new \DateTimeZone(Config::DEFAULT_TIMEZONE));
    }

    /**
     * @param User $user
     * @return LikeReaction
     */
    public static function constructOverload(User $user) : LikeReaction{
        $like = new LikeReaction();
        $like->setUser($user);
        return $like;
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
     * Set dateLiked
     *
     * @param \DateTime $dateLiked
     *
     * @return LikeReaction
     */
    public function setDateLiked($dateLiked)
    {
        $this->dateLiked = $dateLiked;

        return $this;
    }

    /**
     * Get dateLiked
     *
     * @return \DateTime
     */
    public function getDateLiked()
    {
        return $this->dateLiked;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }


}

