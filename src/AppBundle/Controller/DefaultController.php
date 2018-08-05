<?php

namespace AppBundle\Controller;

use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $categories = $this->categoryService->findLocaleCategories();
        $latestPosts = $this->articleService->findArticlesForLatestPosts(0, $categories);
        $sliderArticles = $this->articleService->forgeSliderViewModel($this->articleService->findArticlesByCategories($categories));

        return $this->render('default/index.html.twig', [
            'categories'=>$categories,
            'latestPosts'=>$latestPosts,
            'sliderArticles'=>$sliderArticles,
        ]);
    }

    /**
     * @Route("/contacts", name="contacts_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactsAction(){
        return $this->render("default/contacts.html.twig", array());
    }

    /**
     * @Route("/articles/{id}", name="show_article", defaults={"id"=null})
     * @param Request $request
     * @param $id
     * @param IArticleDbManager $articleDbManager
     * @return Response
     */
    public function viewArticleAction(Request $request, $id, IArticleDbManager $articleDbManager){
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(array('id'=>$id));
        //TODO no checks for invalid id keep in mind!
        $similarArticles = $articleDbManager->findSimilarArticles($article);

        //TODO ajax call increment view
        $article->setViews($article->getViews() + 1);
        $this->getDoctrine()->getManager()->merge($article);
        $this->getDoctrine()->getManager()->flush();

        foreach ($article->getComments() as $comment){
            $a =  $comment->getId();
        }

        return $this->render('default/article.html.twig', [
            'article'=>$article,
            'similarArticles'=>$similarArticles,
            'form'=>$this->createForm(CommentType::class)->createView(),
        ]);
    }


    /**
     * @Route("/admin", name="admin_panel")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * add @ Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hiddenAction(Request $request)
    {
        if(!$this->isAdminLogged())
            return $this->redirectToRoute('homepage');

        return $this->render("partials/haha.html.twig");
    }
}
