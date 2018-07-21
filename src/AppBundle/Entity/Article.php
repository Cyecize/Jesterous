<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var int
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;


    /**
     * @var ArticleCategory
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ArticleCategory", fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;



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


}
