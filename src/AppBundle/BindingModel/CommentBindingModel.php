<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/28/2018
 * Time: 8:36 PM
 */

namespace AppBundle\BindingModel;
use Symfony\Component\Validator\Constraints as Assert;

class CommentBindingModel
{


    private $redirect;

    private $commenterName;

    private $commenterEmail;

    private $content;

    private $articleId;

    private $parentCommentId;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param mixed $redirect
     */
    public function setRedirect($redirect): void
    {
        $this->redirect = $redirect;
    }

    /**
     * @return mixed
     */
    public function getCommenterName()
    {
        return $this->commenterName;
    }

    /**
     * @param mixed $commenterName
     */
    public function setCommenterName($commenterName): void
    {
        $this->commenterName = $commenterName;
    }

    /**
     * @return mixed
     */
    public function getCommenterEmail()
    {
        return $this->commenterEmail;
    }

    /**
     * @param mixed $commenterEmail
     */
    public function setCommenterEmail($commenterEmail): void
    {
        $this->commenterEmail = $commenterEmail;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function setArticleId($articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getParentCommentId()
    {
        return $this->parentCommentId;
    }

    /**
     * @param mixed $parentCommentId
     */
    public function setParentCommentId($parentCommentId): void
    {
        $this->parentCommentId = $parentCommentId;
    }






}