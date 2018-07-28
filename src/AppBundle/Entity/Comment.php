<?php

namespace AppBundle\Entity;

use AppBundle\Constants\Config;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     * @ORM\Column(name="commenter_name", type="string", nullable=false, length=100)
     */
    private $commenterName;

    /**
     * @var string
     * @ORM\Column(name="commenter_email", type="string", nullable=false, length=100)
     */
    private $commenterEmail;


    /**
     * @var Comment
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Comment")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $parentComment;


    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="parentComment")
     */
    private $replies;

    /**
     * @var Article
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $article;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->dateAdded = new \DateTime('now', new \DateTimeZone(Config::DEFAULT_TIMEZONE));
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Comment
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
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getCommenterName(): string
    {
        return $this->commenterName;
    }

    /**
     * @param string $commenterName
     */
    public function setCommenterName(string $commenterName): void
    {
        $this->commenterName = $commenterName;
    }

    /**
     * @return string
     */
    public function getCommenterEmail(): string
    {
        return $this->commenterEmail;
    }

    /**
     * @param string $commenterEmail
     */
    public function setCommenterEmail(string $commenterEmail): void
    {
        $this->commenterEmail = $commenterEmail;
    }


    /**
     * @return Comment
     */
    public function getParentComment()
    {
        return $this->parentComment;
    }

    /**
     * @param Comment $parentComment
     */
    public function setParentComment(Comment $parentComment): void
    {
        $this->parentComment = $parentComment;
    }

    /**
     * @return Comment[]
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * @param Comment[] $replies
     */
    public function setReplies(array $replies): void
    {
        $this->replies = $replies;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * @return User
     */
    public function getUser()
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

