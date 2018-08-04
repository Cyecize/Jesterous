<?php

namespace AppBundle\Entity;

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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\LikeReaction")
     * @ORM\JoinTable(name="likes_quotes", joinColumns={@ORM\JoinColumn(name="quote_id", referencedColumnName="id")}, inverseJoinColumns={@ORM\JoinColumn(name="like_id", referencedColumnName="id")})
     */
    private $likes;

    public function __construct()
    {
        $this->isVisible = false;
        $this->likes = 0;
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

}

