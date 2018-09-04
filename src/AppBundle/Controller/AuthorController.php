<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/2/2018
 * Time: 8:42 AM
 */

namespace AppBundle\Controller;


use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends BaseController
{

    public function __construct(LocalLanguage $language)
    {
        parent::__construct($language);
    }


    /**
     * @Route("/author/panel", name="author_panel")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function authorPanel(Request $request){

        return $this->render('author/panel.html.twig', [
            'info'=>$request->get('info'),
            'error'=>$request->get('error'),
        ]);
    }

}