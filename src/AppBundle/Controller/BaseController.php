<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/9/2018
 * Time: 1:05 AM
 */

namespace AppBundle\Controller;

use AppBundle\Constants\Roles;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{
    private const INVALID_TOKEN =  "Invalid token";

    /**
     * @var string
     */
    protected $currentLang;

    /**
     * @var LocalLanguage
     */
    protected $language;

    public function __construct(LocalLanguage $language)
    {
        $this->currentLang = $language->getLocalLang();
        $this->language = $language;
    }

    protected function isUserLogged(): bool{
        return  $this->get('security.authorization_checker')->isGranted(Roles::ROLE_USER);  //when user is logged
    }

    protected function isAdminLogged(): bool{
        return $this->get('security.authorization_checker')->isGranted(Roles::ROLE_ADMIN, 'ROLES');
    }

    protected function isAuthorLogged(): bool{
        return $this->get('security.authorization_checker')->isGranted(Roles::ROLE_AUTHOR, 'ROLES');
    }

    /**
     * @param $bindingModel
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function validate($bindingModel){
       return $this->get('validator')->validate($bindingModel);
    }

    /**
     * @param Request $request
     * @throws RestFriendlyExceptionImpl
     */
    protected function validateToken(Request $request)  {
        $token = $request->get('token');
        if(!$this->isCsrfTokenValid('token', $token))
            throw new RestFriendlyExceptionImpl(self::INVALID_TOKEN);
    }
}