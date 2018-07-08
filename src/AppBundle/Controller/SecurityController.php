<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 4:44 PM
 */

namespace AppBundle\Controller;

use AppBundle\Constants\Config;
use AppBundle\Constants\Roles;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserRegisterType;
use AppBundle\Repository\RoleRepository;
use AppBundle\Service\UserValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{


    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function loginAction(AuthenticationUtils $authUtils, Request $request)
    {
//        if ($this->isUserLogged())
//            return $this->redirectToRoute("homepage");
        $lastUsername = null;
        $error = $authUtils->getLastAuthenticationError();
        // get the login error if there is one

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        if ($error != null) {
            $error = "Wrong Password!";
            $repo = $this->getDoctrine()->getRepository(User::class);
            $existingUser = $repo->findOneBy(array("username" => $lastUsername));
            if ($existingUser == null)
                $existingUser = $repo->findOneBy(array("email" => $lastUsername));
            if ($existingUser == null) {
                $lastUsername = null;
                $error = "Username or Email does not exist!";
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = new User();
        $userForm = $this->createForm(UserRegisterType::class, $user);
        $userForm->handleRequest($request);
        $error = null;

        if ($userForm->isSubmitted()) {
            //validate Username
            $userInDb = $userRepo->findOneBy(array('username' => $user->getUsername()));
            if ($userInDb != null) {
                $error = "Потребителското име е заето!";
                $user->setUsername("");
                goto escape;
            }
            if (!UserValidator::isUsernameValid($user->getUsername())) {
                $error = "Невалидно потребителско име!";
                $user->setUsername("");
                goto  escape;
            }
            //end validate username
            //validate email
            $email = $userRepo->findOneBy(array('email' => $user->getEmail()));
            if ($email != null) {
                $error = "E-Mail адресът е зает!";
                $user->setEmail("");
                goto  escape;
            }
            if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $error = "Невалиден E-Mail адрес!";
                $user->setEmail("");
                goto  escape;
            }
            //end email validation
            //password validation
            if ($user->getPassword() != $user->getConfPassword()) {
                $error = "Паролите не съвпадат";
                goto  escape;
            }
            if (!UserValidator::isPasswordValid($user->getPassword())) {
                $error = "Паролата е под " . Config::MINIMUM_PASSWORD_LENGTH . " знака";
                goto escape;
            }

            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();

            $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy(array("role" => Roles::ROLE_USER));
            if ($role == null) {
                $role = new Role();
                $role->setRole(Roles::ROLE_USER);
                $entityManager->persist($role);
            }

            $user->setRole($role);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("security_login", []);
        }

        escape:

        return $this->render("security/register.html.twig", array(
            "userform" => $user,
            'form' => $userForm->createView(),
            "error" => $error,
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