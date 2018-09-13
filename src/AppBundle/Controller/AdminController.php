<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 8:24 PM
 */

namespace AppBundle\Controller;


use AppBundle\Service\LocalLanguage;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends BaseController
{
    public function __construct(LocalLanguage $language)
    {
        parent::__construct($language);
    }

    /**
     * @Route("admin/panel", name="admin_panel")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminPanelAction(Request $request){
        return $this->render('admin/panel.html.twig', [
            'info'=>$request->get('info'),
            'error'=>$request->get('error'),
        ]);
    }
}