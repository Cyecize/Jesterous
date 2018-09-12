<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/11/2018
 * Time: 6:27 PM
 */

namespace AppBundle\Controller;


use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\User;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SubscribeAndFollowController extends BaseController
{
    private const USER_NOT_FOUND = "User was not found!";
    private const CANNOT_FOLLOW_YOURSELF = "Cannot follow yourself!";

    /**
     * @var IUserDbManager
     */
    private $userService;

    public function __construct(LocalLanguage $language, IUserDbManager $userDbManager)
    {
        parent::__construct($language);
        $this->userService = $userDbManager;
    }

    /**
     * @Route("/author/followers/my", name="author_followers")
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function myFollowersAction(){
        $user  = $this->userService->findOneById($this->getUser()->getId());
        return $this->render('author/followers/my-followers.html.twig',
            [
               'author'=>$user,
            ]);
    }

    /**
     * @Route("/follow/{celebId}", name="follow_someone", defaults={"celebId"=null}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $celebId
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     */
    public function followUserAction($celebId){
        $targetUser = $this->userService->findOneById($this->getUser()->getId());
        $celebrity = $this->getCelebrity($celebId, $this->getUser());
        $this->userService->addFollower($targetUser, $celebrity);
        return new JsonResponse(["message"=>sprintf("%s was followed!", $celebrity->getId())]);
    }

    /**
     * @Route("/unfollow/{celebId}", name="unfollow_someone", defaults={"celebId"=null}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $celebId
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     */
    public function unfollowUserAction($celebId){
        $targetUser = $this->userService->findOneById($this->getUser()->getId());
        $celebrity = $this->getCelebrity($celebId, $this->getUser());
        $this->userService->removeFollower($targetUser, $celebrity);
        return new JsonResponse(["message"=>sprintf("%s was unfollowed!", $celebrity->getId())]);
    }

    /**
     * @param $celebId
     * @param User $targetUser
     * @return User
     * @throws RestFriendlyExceptionImpl
     */
    private function getCelebrity($celebId, User $targetUser) : User {
        $celebrity = $this->userService->findOneById($celebId);
        if($celebrity == null)
            throw new RestFriendlyExceptionImpl(self::USER_NOT_FOUND, 404);
        if($celebrity->getId() == $targetUser->getId())
            throw new RestFriendlyExceptionImpl(self::CANNOT_FOLLOW_YOURSELF);
        return $celebrity;
    }
}