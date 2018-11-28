<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 9:48 AM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Entity\Article;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;

interface INotificationSenderManager
{
    /**
     * @param User $user
     */
    public function onUserRegister(User $user): void;

    /**
     * @param User $follower
     * @param User $celebrity
     * @throws IllegalArgumentException
     */
    public function onFollow(User $follower, User $celebrity) : void ;

    /**
     * @param Article $article
     */
    public function onNewBlogPost(Article $article): void;

    /**
     * @param User $follower
     * @param User $author
     * @param string $message
     * @throws IllegalArgumentException
     */
    public function notifyFollower(User $follower, User $author, string $message): void;

    /**
     * @param string $message
     * @param string $href
     */
    public function notifyAll(string $message, string $href): void;

    /**
     * @param UserFeedbackBindingModel $bindingModel
     * @param Question $question
     */
    public function onFeedback(UserFeedbackBindingModel $bindingModel, Question $question) : void ;

    /**
     * @param User $target
     * @param User $commenter
     * @param string $href
     */
    public function onComment(User $target, User $commenter, string $href) : void ;

}