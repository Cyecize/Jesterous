<?php

namespace AppBundle\Controller;

use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Contracts\IArticleAsMessageManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Contracts\IMailingManager;
use AppBundle\Contracts\INotificationSenderManager;
use AppBundle\Contracts\IQuestionDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\User;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Form\FeedbackType;
use AppBundle\Service\LocalLanguage;
use AppBundle\Util\Pageable;
use AppBundle\Util\PageRequest;
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
     * @param IArticleAsMessageManager $articleAsMessage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, IArticleAsMessageManager $articleAsMessage)
    {
        $lang = $request->get('lang');
        if ($lang != null)
            $this->language->setLang($lang);
        $categories = $this->categoryService->findAllLocalCategories();
        $latestPosts = $this->articleService->findArticlesByCategories(new Pageable($request), $categories);

        $sliderArticles = $this->articleService->forgeSliderViewModel($latestPosts->getElements());
        $trendingArticles = $this->articleService->findArticlesByCategories(new PageRequest(1, 7), $categories)->getElements();

        $subscribeMsg = null;
        try {
            $subscribeMsg = $articleAsMessage->getSubscribeMessage($this->currentLang)->getMainContent();
        } catch (ArticleNotFoundException $e) {
        }

        return $this->render('default/index.html.twig', [
            'categories' => $categories,
            'latestPosts' => $latestPosts,
            'sliderArticles' => $sliderArticles,
            'trendingArticles' => $trendingArticles,
            'error' => $request->get('error'),
            'info' => $request->get('info'),
            'subscribeMessage' => $subscribeMsg
        ]);
    }

    /**
     * @Route("/contacts", name="contacts_page")
     * @param Request $request
     * @param IQuestionDbManager $questionDbManager
     * @param IUserDbManager $userDb
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function contactsAction(Request $request, IQuestionDbManager $questionDbManager, IUserDbManager $userDb)
    {
        $info = null;
        $bindingModel = new UserFeedbackBindingModel();
        $form = $this->createForm(FeedbackType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto escape;

            $user = null;
            if ($this->getUser() != null)
                $user = $userDb->findOneById($this->getUserId());

            $question = $questionDbManager->createQuestion($bindingModel, $user);
            $this->mailingService->sendFeedback($bindingModel);
            $this->notificationSenderService->onFeedback($bindingModel, $question);

            $info = $this->language->messageWasSent();
            $bindingModel = new UserFeedbackBindingModel();
        }

        escape:
        return $this->render("default/contacts.html.twig", array(
            'form1' => $form->createView(),
            'bindingModel' => $bindingModel,
            'info' => $info
        ));
    }

    /**
     * @Route("/privacy", name="privacy_policy")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function privacy()
    {
        return $this->render('default/privacy-policy.html.twig');
    }

}
