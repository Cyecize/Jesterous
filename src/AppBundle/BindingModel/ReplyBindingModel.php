<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/3/2018
 * Time: 4:24 PM
 */

namespace AppBundle\BindingModel;


class ReplyBindingModel
{

    private $redirect;

    private $content;

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