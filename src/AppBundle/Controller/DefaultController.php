<?php

namespace AppBundle\Controller;

use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Constants\Roles;
use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Form\CommentType;
use AppBundle\Service\ArticleCategoryDbManager;
use AppBundle\Service\ArticleDbManager;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{

    private $articleService;

    private $categoryService;

    public function __construct(LocalLanguage $language, IArticleCategoryDbManager $categoryDbManager, IArticleDbManager $articleDb)
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
        $categories = $this->categoryService->findLocaleCategories();
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

//    /**
//     * @Route("/admin", name="admin_panel")
//     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
//     * add @ Security("has_role('ROLE_ADMIN')")
//     * @param Request $request
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function hiddenAction(Request $request)
//    {
//        if(!$this->isAdminLogged())
//            return $this->redirectToRoute('homepage');
//
//        return $this->render("partials/haha.html.twig");
//    }
}
