<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\DependencyInjection\Tests\Fixtures\factoryFunction;

/**
 * Quote
 *
 * @ORM\Table(name="quotes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuoteRepository")
 */
class Quote
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
     * @ORM\Column(name="bg_author_name", type="string", length=255)
     */
    private $bgAuthorName;

    /**
     * @var string
     *
     * @ORM\Column(name="en_author_name", type="string", length=255)
     */
    private $enAuthorName;

    /**
     * @var string
     *
     * @ORM\Column(name="bg_quote", type="text")
     */
    private $bgQuote;

    /**
     * @var string
     *
     * @ORM\Column(name="en_quote", type="text")
     */
    private $enQuote;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_visible", type="boolean")
     */
    private $isVisible;


    /**
     * @var LikeReaction[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\LikeReaction", fetch="EAGER")
     * @ORM\JoinTable(name="likes_quotes", joinColumns={@ORM\JoinColumn(name="quote_id", referencedColumnName="id", onDelete="CASCADE")}, inverseJoinColumns={@ORM\JoinColumn(name="like_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    private $likes;

    public function __construct()
    {
        $this->isVisible = false;
        $this->likes = new ArrayCollection();
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
     * Set bgAuthorName
     *
     * @param string $bgAuthorName
     *
     * @return Quote
     */
    public function setBgAuthorName($bgAuthorName)
    {
        $this->bgAuthorName = $bgAuthorName;

        return $this;
    }

    /**
     * Get bgAuthorName
     *
     * @return string
     */
    public function getBgAuthorName()
    {
        return $this->bgAuthorName;
    }

    /**
     * Set enAuthorName
     *
     * @param string $enAuthorName
     *
     * @return Quote
     */
    public function setEnAuthorName($enAuthorName)
    {
        $this->enAuthorName = $enAuthorName;

        return $this;
    }

    /**
     * Get enAuthorName
     *
     * @return string
     */
    public function getEnAuthorName()
    {
        return $this->enAuthorName;
    }

    /**
     * Set bgQuote
     *
     * @param string $bgQuote
     *
     * @return Quote
     */
    public function setBgQuote($bgQuote)
    {
        $this->bgQuote = $bgQuote;

        return $this;
    }

    /**
     * Get bgQuote
     *
     * @return string
     */
    public function getBgQuote()
    {
        return $this->bgQuote;
    }

    /**
     * Set enQuote
     *
     * @param string $enQuote
     *
     * @return Quote
     */
    public function setEnQuote($enQuote)
    {
        $this->enQuote = $enQuote;

        return $this;
    }

    /**
     * Get enQuote
     *
     * @return string
     */
    public function getEnQuote()
    {
        return $this->enQuote;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return Quote
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return bool
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * @return LikeReaction[]
     */
    public function getLikes(): ArrayCollection
    {
        return $this->likes->unwrap();
    }

    /**
     * @param LikeReaction[] $likes
     */
    public function setLikes(array $likes): void
    {
        $this->likes = $likes;
    }

    public function addLike(LikeReaction $like = null)
    {
        $this->likes->add($like);
    }

    public function removeLike(LikeReaction $likeReaction = null)
    {
        $this->likes->remove($likeReaction);
    }

    public function hasUserLiked(User $user): bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser()->getId() == $user->getId())
                return true;
        }
        return false;
    }

}

