<?php

namespace AppBundle\Controller;

use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Contracts\IMailingManager;
use AppBundle\Contracts\INotificationSenderManager;
use AppBundle\Entity\User;
use AppBundle\Form\FeedbackType;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @var IArticleDbManager
     */
    private $articleService;

    /**
     * @var ICategoryDbManager
     */
    private $categoryService;

    /**
     * @var IMailingManager
     */
    private $mailingService;

    /**
     * @var INotificationSenderManager
     */
    private $notificationSenderService;


    public function __construct(LocalLanguage $language, ICategoryDbManager $categoryDbManager, IArticleDbManager $articleDb, IMailingManager $mailing, INotificationSenderManager $notificationSender)
    {
        parent::__construct($language);
        $this->articleService = $articleDb;
        $this->categoryService = $categoryDbManager;
        $this->mailingService = $mailing;
        $this->notificationSenderService = $notificationSender;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $lang = $request->get('lang');
        if ($lang != null)
            $this->language->setLang($lang);
        $categories = $this->categoryService->findAllLocalCategories();
        $latestPosts = $this->articleService->findArticlesForLatestPosts(0, $categories);
        $sliderArticles = $this->articleService->forgeSliderViewModel($this->articleService->findArticlesByCategories($categories, 4));
        $trendingArticles = $this->articleService->findArticlesByCategories($categories, 7);

        return $this->render('default/index.html.twig', [
            'categories' => $categories,
            'latestPosts' => $latestPosts,
            'sliderArticles' => $sliderArticles,
            'trendingArticles' => $trendingArticles,
            'error' => $request->get('error'),
            'info' => $request->get('info'),
        ]);
    }

    /**
     * @Route("/contacts", name="contacts_page")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function contactsAction(Request $request)
    {
        $info = null;
        $bindingModel = new UserFeedbackBindingModel();
        $form = $this->createForm(FeedbackType::class, $bindingModel);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $this->validateToken($request);
            if(count($this->get('validator')->validate($bindingModel)) > 0)
                goto escape;
            $this->notificationSenderService->onFeedback($bindingModel);
            $this->mailingService->sendFeedback($bindingModel);
            $info = $this->language->messageWasSent();
            $bindingModel = new UserFeedbackBindingModel();
        }

        escape:
        return $this->render("default/contacts.html.twig", array(
            'form1'=>$form->createView(),
            'bindingModel'=>$bindingModel,
            'info'=>$info
        ));
    }

}
