<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/12/2018
 * Time: 11:57 AM
 */

namespace AppBundle\Controller;


use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Form\EditPasswordType;
use AppBundle\Form\ImageType;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class UserController extends BaseController
{

    /**
     * @var IUserDbManager
     */
    private $userService;

    private $articleService;

    public function __construct(LocalLanguage $language, IUserDbManager $userDbManager, IArticleDbManager $articleDbManager)
    {
        parent::__construct($language);
        $this->userService = $userDbManager;
        $this->articleService = $articleDbManager;
    }

    /**
     * @Route("/users/show/{username}", name="user_details", defaults={"username"=null})
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws UserNotFoundException
     */
    public function showProfileAction($username)
    {
        $user = $this->userService->findOneByUsername($username);
        if ($username == null)
            throw new UserNotFoundException($this->language->userNotFound($username));
        return $this->render('user/user-details.html.twig',
            [
                'user' => $user,
                'articles' => $this->articleService->findUserArticles($user),
            ]);
    }

    /**
     * @Route("/user/panel", name="user_panel")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profilePanelAction(Request $request)
    {
        return $this->render('user/panel.html.twig', [
            'info' => $request->get('info'),
            'error' => $request->get('error')
        ]);
    }

    /**
     * @Route("/users/password/edit", name="change_password")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function changePasswordAction(Request $request)
    {
        $bindingModel = new ChangePasswordBindingModel();
        $form = $this->createForm(EditPasswordType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto  escape;
            try {
                $this->userService->changePassword($this->userService->findOneById($this->getUser()->getId()), $bindingModel);
                return $this->redirectToRoute('security_logout');
            } catch (IllegalArgumentException $e) {
                return $this->redirectToRoute('change_password', [
                    'error' => $this->language->forName($e->getMessage()),
                ]);
            }
        }

        escape:
        return $this->render('user/profile/change-password.html.twig', [
            'error' => $request->get('error'),
            'form1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/profile-picture", name="change_profile_picture")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function changeProfileImageAction(Request $request)
    {

        $bindingModel = new ImageBindingModel();
        $form = $this->createForm(ImageType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto  escape;
            $this->userService->changeProfilePicture($this->userService->findOneById($this->getUser()->getId()), $bindingModel);
            return $this->redirectToRoute('user_panel');
        }

        escape:
        return $this->render('user/profile/change-profile-image.html.twig', [
            'form1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/profile/destroy", name="destroy_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws IllegalArgumentException
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function removeProfileAction(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $userId = $this->getUser()->getId();
            $this->validateToken($request);
            $this->get('security.token_storage')->setToken(null);
            $this->get('session')->invalidate();
            $this->userService->removeAccount($this->userService->findOneById($userId));
            return $this->redirectToRoute('homepage');
        }
        return $this->render('user/profile/remove-profile.html.twig', []);
    }

    /**
     * @Route("/users/about-me/edit", name="edit_summary")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function aboutMeAction(Request $request) {

        if($request->getMethod() == "POST"){
            $this->validateToken($request);
            $summary = $request->get('summary');
            if($summary == null || strlen($summary) > 1000)
                return $this->redirectToRoute('edit_summary', ['error'=>$this->language->fieldCannotBeEmpty()]);
            $user = $this->userService->findOneById($this->getUser()->getId());
            $user->setUserDescription($summary);
            $this->userService->save($user);
            return $this->redirectToRoute('user_panel');
        }

        return $this->render('user/profile/about-me.html.twig', [
            'error'=>$request->get('error'),
        ]);
    }
}