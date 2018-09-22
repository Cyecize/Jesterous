<?php

namespace AppBundle\Entity;

use AppBundle\Constants\Config;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255, nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="background_image_link", type="string", length=255, nullable=true)
     */
    private $backgroundImageLink;

    /**
     * @var string
     *
     * @ORM\Column(name="main_content", type="text", nullable=true)
     */
    private $mainContent;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_visible", type="boolean")
     */
    private $isVisible;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_notified", type="boolean", options={"default":false})
     */
    private $isNotified;

    /**
     * @var int
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views;

    /**
     * @var int
     * @ORM\Column(name="daily_views", type="integer",  options={"default":0})
     */
    private $dailyViews;

    /**
     * @var float
     * @ORM\Column(name="estimated_read_time", type="float", options={"default":0})
     */
    private $estimatedReadTime;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $author;


    /**
     * @var ArticleCategory
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ArticleCategory", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;


    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="article")
     */
    private $comments;

    /**
     * @var Tag[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag")
     * @ORM\JoinTable(name="articles_tags", joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    private $tags;


    public function __construct()
    {
        $this->dateAdded = new \DateTime('now', new \DateTimeZone(Config::DEFAULT_TIMEZONE));
        $this->views = 0;
        $this->dailyViews = 0;
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->isVisible = false;
        $this->isNotified = false;
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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Article
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Article
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set backgroundImageLink
     *
     * @param string $backgroundImageLink
     *
     * @return Article
     */
    public function setBackgroundImageLink($backgroundImageLink)
    {
        $this->backgroundImageLink = $backgroundImageLink;

        return $this;
    }

    /**
     * Get backgroundImageLink
     *
     * @return string
     */
    public function getBackgroundImageLink()
    {
        return $this->backgroundImageLink;
    }

    /**
     * Set mainContent
     *
     * @param string $mainContent
     *
     * @return Article
     */
    public function setMainContent($mainContent)
    {
        $this->mainContent = $mainContent;

        return $this;
    }

    /**
     * Get mainContent
     *
     * @return string
     */
    public function getMainContent()
    {
        return $this->mainContent;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return Article
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
     * @return bool
     */
    public function isNotified(): bool
    {
        return $this->isNotified;
    }

    /**
     * @param bool $isNotified
     */
    public function setIsNotified(bool $isNotified): void
    {
        $this->isNotified = $isNotified;
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return Article
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @return int
     */
    public function getDailyViews(): int
    {
        return $this->dailyViews;
    }

    /**
     * @param int $dailyViews
     */
    public function setDailyViews(int $dailyViews): void
    {
        $this->dailyViews = $dailyViews;
    }

    /**
     * @param float $estimatedReadTime
     */
    public function setEstimatedReadTime(float $estimatedReadTime): void
    {
        $this->estimatedReadTime = $estimatedReadTime;
    }

    /**
     * @return float
     */
    public function getEstimatedReadTime(): float
    {
        return $this->estimatedReadTime;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return ArticleCategory
     */
    public function getCategory(): ArticleCategory
    {
        return $this->category;
    }

    /**
     * @param ArticleCategory $category
     */
    public function setCategory(ArticleCategory $category): void
    {
        $this->category = $category;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        //TODO improve autocomplete
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    //overrides
    public  function __toString()
    {
        return $this->id  . "";
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag[] $tags
     */
    public function setTags( $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag){
        $this->tags->add($tag);
    }


}

