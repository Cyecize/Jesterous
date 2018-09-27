<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/25/2018
 * Time: 6:14 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\ArticleAsMessageBindingModel;
use AppBundle\Contracts\IArticleAsMessageManager;
use AppBundle\Form\ArticleAsMessageType;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class ArticleAsMessageController extends BaseController
{
    /**
     * @var IArticleAsMessageManager
     */
    private $aamService;

    public function __construct(LocalLanguage $language, IArticleAsMessageManager $articleAsMessage)
    {
        parent::__construct($language);
        $this->aamService = $articleAsMessage;
    }

    /**
     * @Route("/admin/article-as-a-message",name="article_as_a_message")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function showSettingsAction(Request $request)
    {
        $bindingModel = new ArticleAsMessageBindingModel();
        $form = $this->createForm(ArticleAsMessageType::class, $bindingModel);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto  escape;
            $this->aamService->saveSettings($bindingModel);
            return $this->redirectToRoute('admin_panel', ['info'=>'Settings Saved!']);
        }

        escape:
        return $this->render('admin/article-as-message/article-as-message.html.twig', [
            'settings' => $this->aamService->getArticleSettings(),
            'form1' => $form->createView()
        ]);
    }
}