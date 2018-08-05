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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param IArticleDbManager $articleDbManager
     * @param IArticleCategoryDbManager $articleCategoryDbManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, IArticleDbManager $articleDbManager, IArticleCategoryDbManager $articleCategoryDbManager)
    {
        $categories = $articleCategoryDbManager->findLocaleCategories();
        $latestPosts = $articleDbManager->findArticlesForLatestPosts(0);
        $sliderArticles = $articleDbManager->forgeSliderViewModel($articleDbManager->findArticlesByCategories($articleCategoryDbManager->findLocaleCategories()));

        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
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
