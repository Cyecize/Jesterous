<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/25/2018
 * Time: 1:24 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class ArticleAsMessageBindingModel
{
    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\Regex("/^\d+$/")
     */
    private $subscribe_reward__bg_article;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\Regex("/^\d+$/")
     */
    private $subscribe_reward__en_article;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\Regex("/^\d+$/")
     */
    private $subscribe_message__bg_article;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\Regex("/^\d+$/")
     */
    private $subscribe_message__en_article;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getSubscribeRewardBgArticle()
    {
        return intval($this->subscribe_reward__bg_article);
    }

    /**
     * @param mixed $subscribe_reward__bg_article
     */
    public function setSubscribeRewardBgArticle($subscribe_reward__bg_article): void
    {
        $this->subscribe_reward__bg_article = $subscribe_reward__bg_article;
    }

    /**
     * @return mixed
     */
    public function getSubscribeRewardEnArticle()
    {
        return intval($this->subscribe_reward__en_article);
    }

    /**
     * @param mixed $subscribe_reward__en_article
     */
    public function setSubscribeRewardEnArticle($subscribe_reward__en_article): void
    {
        $this->subscribe_reward__en_article = $subscribe_reward__en_article;
    }

    /**
     * @return mixed
     */
    public function getSubscribeMessageBgArticle()
    {
        return intval($this->subscribe_message__bg_article);
    }

    /**
     * @param mixed $subscribe_message__bg_article
     */
    public function setSubscribeMessageBgArticle($subscribe_message__bg_article): void
    {
        $this->subscribe_message__bg_article = $subscribe_message__bg_article;
    }

    /**
     * @return mixed
     */
    public function getSubscribeMessageEnArticle()
    {
        return intval($this->subscribe_message__en_article);
    }

    /**
     * @param mixed $subscribe_message__en_article
     */
    public function setSubscribeMessageEnArticle($subscribe_message__en_article): void
    {
        $this->subscribe_message__en_article = $subscribe_message__en_article;
    }


}