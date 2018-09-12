<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/12/2018
 * Time: 11:57 AM
 */

namespace AppBundle\Controller;


use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
}