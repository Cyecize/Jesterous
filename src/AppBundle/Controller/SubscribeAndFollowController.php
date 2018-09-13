<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/11/2018
 * Time: 6:27 PM
 */

namespace AppBundle\Controller;


use AppBundle\BindingModel\NotificationBindingModel;
use AppBundle\Contracts\IGlobalSubscriberDbManager;
use AppBundle\Contracts\INotificationSenderManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\User;
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

    public function __construct(LocalLanguage $language, IUserDbManager $userDbManager, IGlobalSubscriberDbManager $globalSubscriberDb, INotificationSenderManager $notificationSender)
    {
        parent::__construct($language);
        $this->userService = $userDbManager;
        $this->subscriberDbService = $globalSubscriberDb;
        $this->notificationSendService = $notificationSender;
    }

    /**
     * @Route("/subscribe/global", name="subscribe_global", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function subscribeToWebsiteAction(Request $request)
    {
        $mail = $request->get('email');
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || !$this->isCsrfTokenValid('token', $request->get('token')))
            return $this->redirectToRoute('homepage', ['error' => $this->language->invalidEmailAddress()]);
        try {
            $this->subscriberDbService->createSubscriber($mail);
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
        $user = $this->userService->findOneById($this->getUser()->getId());
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
    public function notifyAllFollowersAction(Request $request)
    {
        $bindingModel = new NotificationBindingModel();
        $form = $this->createForm(NotificationType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (count($this->get('validator')->validate($bindingModel)) > 0)
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
     * @Route("/follow/{celebId}", name="follow_someone", defaults={"celebId"=null}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $celebId
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     * @throws IllegalArgumentException
     */
    public function followUserAction($celebId)
    {
        $targetUser = $this->userService->findOneById($this->getUser()->getId());
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
        $targetUser = $this->userService->findOneById($this->getUser()->getId());
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