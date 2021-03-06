<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/11/2018
 * Time: 6:27 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\NotificationBindingModel;
use AppBundle\Contracts\IArticleAsMessageManager;
use AppBundle\Contracts\IGlobalSubscriberDbManager;
use AppBundle\Contracts\IMailingManager;
use AppBundle\Contracts\IMailSenderManager;
use AppBundle\Contracts\INotificationSenderManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\User;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Form\NotificationType;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SubscribeAndFollowController extends BaseController
{
    private const USER_NOT_FOUND = "User was not found!";
    private const CANNOT_FOLLOW_YOURSELF = "Cannot follow yourself!";
    private const EMPTY_FIELDS = "Some fields were left empty";

    /**
     * @var IUserDbManager
     */
    private $userService;

    /**
     * @var IGlobalSubscriberDbManager
     */
    private $subscriberDbService;

    /**
     * @var INotificationSenderManager
     */
    private $notificationSendService;

    /**
     * @var IMailingManager
     */
    private $mailingManager;

    public function __construct(LocalLanguage $language, IUserDbManager $userDbManager, IGlobalSubscriberDbManager $globalSubscriberDb, INotificationSenderManager $notificationSender, IMailingManager $mailSender)
    {
        parent::__construct($language);
        $this->userService = $userDbManager;
        $this->subscriberDbService = $globalSubscriberDb;
        $this->notificationSendService = $notificationSender;
        $this->mailingManager = $mailSender;
    }

    /**
     * @Route("/subscribe", name="subscribe_page")
     * @param IArticleAsMessageManager $articleAsMessage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscribePageAction(IArticleAsMessageManager $articleAsMessage)
    {
        $articleContent = null;
        try {
            $articleContent = $articleAsMessage->getSubscribeMessage($this->currentLang)->getMainContent();
        } catch (ArticleNotFoundException $e) {
        }
        return $this->render('default/subscribe-page.html.twig', [
            'articleContent'=>$articleContent,
        ]);
    }

    /**
     * @Route("/unsubscribe", name="unsubscribe")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unsubscribeAction(Request $request)
    {
        $email = $request->get('email');
        if ($email == null)
            goto escape;
        $subscriber = $this->subscriberDbService->findOneByEmail($email);
        if ($subscriber == null)
            goto  escape;
        $this->subscriberDbService->unsubscribe($subscriber);

        return $this->redirectToRoute('homepage', ['info' => $this->language->successfullyUnsubscribed()]);
        escape:
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/subscribe/global", name="subscribe_global", methods={"POST"})
     * @param Request $request
     * @param IArticleAsMessageManager $articleAsMessage
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function subscribeToWebsiteAction(Request $request, IArticleAsMessageManager $articleAsMessage)
    {
        $mail = $request->get('email');
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || !$this->isCsrfTokenValid('token', $request->get('token')))
            return $this->redirectToRoute('homepage', ['error' => $this->language->invalidEmailAddress()]);
        try {
            $sub = $this->subscriberDbService->createSubscriber($mail);
            try {
                $article = $articleAsMessage->getSubscribeReward($this->currentLang);
                $this->mailingManager->sendMessageToNewSubscriber($sub, $article);
            } catch (ArticleNotFoundException $e) {
            }
        } catch (IllegalArgumentException $e) {
            return $this->redirectToRoute('homepage', ['error' => $e->getMessage()]);
        }
        return $this->render('default/subscribe-successful.html.twig');
    }

    /**
     * @Route("/author/followers/my", name="author_followers")
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function myFollowersAction()
    {
        $user = $this->userService->findOneById($this->getUserId());
        return $this->render('author/followers/my-followers.html.twig',
            [
                'author' => $user,
            ]);
    }

    /**
     * @Route("/author/followers/notify", name="notify_follower", methods={"POST"})
     * @Security("has_role('ROLE_AUTHOR')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws IllegalArgumentException
     */
    public function notifyFollowerAction(Request $request)
    {
        try {
            $this->validateToken($request);
        } catch (RestFriendlyExceptionImpl $e) {
            throw new IllegalArgumentException($e->getMessage());
        }
        $user = $this->userService->findOneById(intval($request->get('userId')));
        $message = $request->get('message');
        if ($user == null || $message == null || strlen($message) > 255)
            throw new IllegalArgumentException(self::EMPTY_FIELDS);
        $this->notificationSendService->notifyFollower($user, $this->getUser(), $message);
        return $this->redirectToRoute('author_panel', ['info' => sprintf("Notified %s!", $user->getUsername())]);
    }

    /**
     * @Route("/admin/notify/all", name="notify_all")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notifyAllUsersAction(Request $request)
    {
        $bindingModel = new NotificationBindingModel();
        $form = $this->createForm(NotificationType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (count($this->validate($bindingModel)) > 0)
                goto escape;
            $this->notificationSendService->notifyAll($bindingModel->getMessage(), $bindingModel->getHref());
            return $this->redirectToRoute('admin_panel', ['info' => "Message was send!"]);
        }

        escape:
        return $this->render('admin/users/send-notification.html.twig', [
            'form1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/email/all" , name="email_all_post", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function emailAllSubscribersAction(Request $request)
    {
        $subject = $request->get('subject');
        $message = $request->get('message');
        if ($subject == null || $message == null)
            return $this->redirectToRoute('email_all_get', ['error' => "Fill all fields!"]);
        $this->mailingManager->sendMessageToSubscribers($this->getUser(), $subject, $message);
        return $this->redirectToRoute("admin_panel", ['info' => "message sent!"]);
    }

    /**
     * @Route("/admin/email/all" , name="email_all_get", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function emailAllRequest()
    {
        return $this->render('admin/users/send-email.html.twig', [

        ]);
    }

    /**
     * @Route("/follow/{celebId}", name="follow_someone", defaults={"celebId"=null}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $celebId
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     * @throws IllegalArgumentException
     */
    public function followUserAction($celebId)
    {
        $targetUser = $this->userService->findOneById($this->getUserId());
        $celebrity = $this->getCelebrity($celebId, $this->getUser());
        $this->userService->addFollower($targetUser, $celebrity);
        $this->notificationSendService->onFollow($targetUser, $celebrity);
        return new JsonResponse(["message" => sprintf("%s was followed!", $celebrity->getId())]);
    }

    /**
     * @Route("/unfollow/{celebId}", name="unfollow_someone", defaults={"celebId"=null}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $celebId
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     */
    public function unfollowUserAction($celebId)
    {
        $targetUser = $this->userService->findOneById($this->getUserId());
        $celebrity = $this->getCelebrity($celebId, $this->getUser());
        $this->userService->removeFollower($targetUser, $celebrity);
        return new JsonResponse(["message" => sprintf("%s was unfollowed!", $celebrity->getId())]);
    }

    /**
     * @param $celebId
     * @param User $targetUser
     * @return User
     * @throws RestFriendlyExceptionImpl
     */
    private function getCelebrity($celebId, User $targetUser): User
    {
        $celebrity = $this->userService->findOneById($celebId);
        if ($celebrity == null)
            throw new RestFriendlyExceptionImpl(self::USER_NOT_FOUND, 404);
        if ($celebrity->getId() == $targetUser->getId())
            throw new RestFriendlyExceptionImpl(self::CANNOT_FOLLOW_YOURSELF);
        return $celebrity;
    }
}