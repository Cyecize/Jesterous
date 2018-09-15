<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/12/2018
 * Time: 11:57 AM
 */

namespace AppBundle\Controller;


use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Form\EditPasswordType;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

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
}