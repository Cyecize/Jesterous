<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 9:56 AM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Constants\Roles;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\INotificationDbManager;
use AppBundle\Contracts\INotificationSenderManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;

class NotificationSenderManager implements INotificationSenderManager
{
    private const ON_USER_REGISTER_FORMAT = "%s has joined the blog. See his details.";
    private const ON_USER_REGISTER_HREF_FORMAT = "/users/show/%s";
    private const ON_NEW_ARTICLE_FORMAT = "/articles/%d";
    private const USER_NOT_FOLLOWER = "User does not follow you!";
    private const USER_FOLLOWED_YOU_FORMAT = "%s just followed you!";
    private const ON_FEEDBACK_FORMAT = "%s with email %s has asked something, check your email!";

    /**
     * @var INotificationDbManager
     */
    private $notificationDbService;

    /**
     * @var IUserDbManager
     */
    private $userService;

    /**
     * @var LocalLanguage
     */
    private $lang;

    /**
     * @var IArticleDbManager
     */
    private $articleService;


    public function __construct(INotificationDbManager $notificationDbService, IUserDbManager $userService, LocalLanguage $lang, IArticleDbManager $articleDbManager)
    {
        $this->notificationDbService = $notificationDbService;
        $this->userService = $userService;
        $this->lang = $lang;
        $this->articleService = $articleDbManager;
    }

    public function onUserRegister(User $user): void
    {
        $tartegUsers = $this->userService->findByRole(Roles::ROLE_ADMIN);
        $msg = sprintf(self::ON_USER_REGISTER_FORMAT, $user->getUsername());
        $link = sprintf(self::ON_USER_REGISTER_HREF_FORMAT, $user->getUsername());
        foreach ($tartegUsers as $tartegUser) {
            $this->notificationDbService->sendNotification($tartegUser, $msg, $link);
        }
    }

    public function onFollow(User $follower, User $celebrity): void
    {
        if (!$follower->isFollowing($celebrity))
            throw new IllegalArgumentException(self::USER_NOT_FOLLOWER);
        $this->notificationDbService->sendNotification($celebrity, sprintf(self::USER_FOLLOWED_YOU_FORMAT, $follower->getUsername()), sprintf(self::ON_USER_REGISTER_HREF_FORMAT, $follower->getUsername()));
    }

    public function onNewBlogPost(Article $article): void
    {
        if ($article->isNotified())
            return;
        $author = $article->getAuthor();
        $followers = $author->getFollowers();
        $msg = sprintf($this->lang->newArticleFormat(), $author->getUsername(), $article->getTitle());
        $link = sprintf(self::ON_NEW_ARTICLE_FORMAT, $article->getId());
        foreach ($followers as $follower) {
            $this->notificationDbService->sendNotification($follower, $msg, $link);
        }
        $article->setIsNotified(true);
        $this->articleService->saveArticle($article);
    }

    public function notifyFollower(User $follower, User $author, string $message): void
    {
        if (!$follower->isFollowing($author))
            throw new IllegalArgumentException(self::USER_NOT_FOLLOWER);
        $this->notificationDbService->sendNotification($follower, $message, "#");
    }

    public function notifyAll(string $message, string $href): void
    {
        $users = $this->userService->findAll();
        foreach ($users as $user) {
            $this->notificationDbService->sendNotification($user, $message, $href);
        }
    }

    public function onFeedback(UserFeedbackBindingModel $bindingModel): void
    {
        $admins = $this->userService->findByRole(Roles::ROLE_ADMIN);
        foreach ($admins as $admin) {
            $this->notificationDbService->sendNotification($admin, sprintf(self::ON_FEEDBACK_FORMAT, $bindingModel->getName(), $bindingModel->getEmail()), "#");
        }
    }

    public function onComment(User $target, User $commenter, string $href): void
    {
        $this->notificationDbService->sendNotification($target, sprintf($this->lang->commentMessageFormat(), $commenter->getUsername()),  $href);
    }
}