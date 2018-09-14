<?php

namespace AppBundle\Controller;

use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
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

    public function __construct(LocalLanguage $language, ICategoryDbManager $categoryDbManager, IArticleDbManager $articleDb)
    {
        parent::__construct($language);
        $this->articleService =$articleDb;
        $this->categoryService = $categoryDbManager;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $lang = $request->get('lang');
        if($lang != null)
            $this->language->setLang($lang);
        $categories = $this->categoryService->findAllLocalCategories();
        $latestPosts = $this->articleService->findArticlesForLatestPosts(0, $categories);
        $sliderArticles = $this->articleService->forgeSliderViewModel($this->articleService->findArticlesByCategories($categories, 4));
        $trendingArticles = $this->articleService->findArticlesByCategories( $categories, 7);

        return $this->render('default/index.html.twig', [
            'categories'=>$categories,
            'latestPosts'=>$latestPosts,
            'sliderArticles'=>$sliderArticles,
            'trendingArticles'=>$trendingArticles,
            'error'=>$request->get('error')
        ]);
    }

    /**
     * @Route("/contacts", name="contacts_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactsAction(){
        return $this->render("default/contacts.html.twig", array());
    }

}
