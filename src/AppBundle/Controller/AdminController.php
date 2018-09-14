<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 8:24 PM
 */

namespace AppBundle\Controller;


use AppBundle\Contracts\IRoleDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Service\LocalLanguage;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends BaseController
{
    private const INVALID_FORM_PARAMETERS = "Invalid form parameters!";

    /**
     * @var IUserDbManager
     */
    private $userService;

    /**
     * @var IRoleDbManager
     */
    private $roleService;

    public function __construct(LocalLanguage $language, IUserDbManager $userDbManager, IRoleDbManager $roleDbManager)
    {
        parent::__construct($language);
        $this->userService = $userDbManager;
        $this->roleService = $roleDbManager;
    }

    /**
     * @Route("admin/panel", name="admin_panel")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminPanelAction(Request $request)
    {
        return $this->render('admin/panel.html.twig', [
            'info' => $request->get('info'),
            'error' => $request->get('error'),
        ]);
    }

    /**
     * @Route("/admin/users/all", name="users_observe")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function observeUsersAction()
    {
        $users = $this->userService->findAll();
        return $this->render('admin/users/all-users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/roles/add", name="add_role", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function addRoleAction(Request $request)
    {
        $this->validateToken($request);
        $bind = $this->bindRoleRequest($request);
        try {
            $this->userService->addRole($bind['user'], $bind['role']);
        } catch (IllegalArgumentException $e) {
            throw new RestFriendlyExceptionImpl($e->getMessage());
        }
        return new JsonResponse(['message'=>"Role added"]);
    }

    /**
     * @Route("/admin/roles/remove", name="remove_role", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function removeRoleAction(Request $request)
    {
        $this->validateToken($request);
        $bind = $this->bindRoleRequest($request);
        try {
            $this->userService->removeRole($bind['user'], $bind['role']);
        } catch (IllegalArgumentException $e) {
            throw new RestFriendlyExceptionImpl($e->getMessage());
        }
        return new JsonResponse(['message'=>"Role removed"]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws RestFriendlyExceptionImpl
     */
    private function bindRoleRequest(Request $request): array
    {
        $roleType = $request->get('roleType');
        $userId = $request->get('userId');
        if ($roleType == null || $userId == null)
            throw new RestFriendlyExceptionImpl(self::INVALID_FORM_PARAMETERS);
        $user = $this->userService->findOneById(intval($userId));
        $role = $this->roleService->findByRoleName($roleType);
        if ($user == null || $role == null)
            throw new RestFriendlyExceptionImpl(self::INVALID_FORM_PARAMETERS);
        return ['user'=>$user, 'role'=>$role];
    }
}