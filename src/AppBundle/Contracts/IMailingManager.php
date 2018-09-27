<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 9:29 PM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Entity\Article;
use AppBundle\Entity\GlobalSubscriber;
use AppBundle\Entity\User;

interface IMailingManager
{
    /**
     * @param User $admin
     * @param string $subject
     * @param string $message
     */
    public function sendMessageToSubscribers(User $admin, string $subject, string $message) : void ;

    /**
     * @param Article $article
     */
    public function sendMessageForNewArticle( Article $article) : void ;

    /**
     * @param UserFeedbackBindingModel $bindingModel
     */
    public function sendFeedback(UserFeedbackBindingModel $bindingModel) : void ;

    /**
     * @param GlobalSubscriber $email
     * @param Article $article
     */
    public function sendMessageToNewSubscriber(GlobalSubscriber $email, Article $article) : void ;
}