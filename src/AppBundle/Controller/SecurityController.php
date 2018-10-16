<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 4:44 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\UserRegisterBindingModel;
use AppBundle\Constants\Roles;
use AppBundle\Contracts\IArticleAsMessageManager;
use AppBundle\Contracts\IFirstRunManager;
use AppBundle\Contracts\IGlobalSubscriberDbManager;
use AppBundle\Contracts\IMailingManager;
use AppBundle\Contracts\INotificationSenderManager;
use AppBundle\Contracts\IRoleDbManager;;
use AppBundle\Entity\User;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Form\UserRegisterType;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    /**
     * @var IGlobalSubscriberDbManager
     */
    private $subscriberDbService;

    /**
     * @var INotificationSenderManager
     */
    private $notificationSenderService;

    /**
     * @var IRoleDbManager
     */
    private $roleService;

    /**
     * @var IFirstRunManager
     */
    private $firstRunService;

    public function __construct(LocalLanguage $language, IGlobalSubscriberDbManager $globalSubscriberDb, INotificationSenderManager $notificationSender, IRoleDbManager $roleDb, IFirstRunManager $firstRun)
    {
        parent::__construct($language);
        $this->subscriberDbService = $globalSubscriberDb;
        $this->notificationSenderService = $notificationSender;
        $this->roleService = $roleDb;
        $this->firstRunService = $firstRun;
    }

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("is_anonymous()", message="You are already logged in")
     */

    public function loginAction(AuthenticationUtils $authUtils, Request $request)
    {
        $lastUsername = null;
        $error = $authUtils->getLastAuthenticationError();
        // get the login error if there is one

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        if ($error != null) {
            $error = $this->language->passwordIsIncorrect();
            $repo = $this->getDoctrine()->getRepository(User::class);
            $existingUser = $repo->findOneBy(array("username" => $lastUsername));
            if ($existingUser == null)
                $existingUser = $repo->findOneBy(array("email" => $lastUsername));
            if ($existingUser == null) {
                $lastUsername = null;
                $error = $this->language->usernameDoesNotExist();
            }
        }

        return $this->render("security/login.html.twig",
            array(
                "last_username" => $lastUsername,
                "error" => $error,
            ));
    }

    /**
     * @Route("/register", name="security_register")
     * @param Request $request
     * @param IArticleAsMessageManager $articleAsMessage
     * @param IMailingManager $mailingManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\ArticleNotFoundException
     * @Security("is_anonymous()", message="You are already logged in")
     */
    public function registerAction(Request $request, IArticleAsMessageManager $articleAsMessage, IMailingManager $mailingManager)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $userBindingModel = new UserRegisterBindingModel();

        $bindForm = $this->createForm(UserRegisterType::class, $userBindingModel);
        $bindForm->handleRequest($request);

        $errors = array();
        $error = null;

        if ($bindForm->isSubmitted()) {
            $errors = $this->validate($userBindingModel);
            if (count($errors) > 0)
                goto escape;

            $userInDb = $userRepo->findOneBy(array('username' => $userBindingModel->getUsername()));
            if ($userInDb != null) {
                $error = $this->language->usernameAlreadyTaken();
                $userBindingModel->setUsername("");
                goto escape;
            }

            $userInDbByEmail = $userRepo->findOneBy(array('email' => $userBindingModel->getEmail()));
            if ($userInDbByEmail != null) {
                $error = $this->language->emailAlreadyInUse();
                $userBindingModel->setEmail("");
                goto  escape;
            }

            $user = $userBindingModel->forgeUser();
            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();

            $role = $this->roleService->findByRoleName(Roles::ROLE_USER);
            if ($role == null) {
                $this->firstRunService->initDb();
                $role = $this->roleService->findByRoleName(Roles::ROLE_USER);
            }
            if (count($userRepo->createQueryBuilder('u')->select('u.id')->getQuery()->getResult()) < 1) {
                $user->addRole($this->roleService->findByRoleName(Roles::ROLE_ADMIN));
            }

            $user->addRole($role);

            $entityManager->persist($user);
            $entityManager->flush();
            $globalSub = $this->subscriberDbService->createSubscriberOnRegister($user->getEmail());
            $this->notificationSenderService->onUserRegister($user);
            try {
                $article = $articleAsMessage->getSubscribeReward($this->currentLang);
                $mailingManager->sendMessageToNewSubscriber($globalSub, $article);
            } catch (ArticleNotFoundException $e) {
            }

            return $this->redirectToRoute("security_login", []);
        }

        escape:
        return $this->render("security/register.html.twig", array(
            "userform" => $userBindingModel,
            'form' => $bindForm->createView(),
            'errors' => $errors,
            'error' => $error
        ));

    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout/", name="security_logout")
     * @throws \Exception
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}