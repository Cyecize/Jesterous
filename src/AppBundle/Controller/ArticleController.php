<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/23/2018
 * Time: 10:24 AM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Exception\CommentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Form\CommentType;
use AppBundle\Form\ReplyType;
use AppBundle\Service\ArticleCategoryDbManager;
use AppBundle\Service\ArticleDbManager;
use AppBundle\Service\LocalLanguage;
use AppBundle\ViewModel\CategoriesViewModel;
use Doctrine\ORM\PersistentCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends BaseController
{
    private const INVALID_ARTICLE_TO_VIEW = "Invalid article to view";
    private const ARTICLE_WITH_ID_WAS_NOT_FOUND = "Article with id %s was not found";

    /**
     * @var IArticleDbManager
     */
    private $articleService;

    /**
     * @var ICategoryDbManager
     */
    private $categoryService;

    public function __construct(LocalLanguage $language, IArticleDbManager $articleDbManager, ICategoryDbManager $categoryDbManager)
    {
        parent::__construct($language);
        $this->articleService = $articleDbManager;
        $this->categoryService = $categoryDbManager;
    }

    /**
     * @Route("/articles/{id}", name="show_article", defaults={"id"=null})
     * @param $id
     * @return Response
     * @throws ArticleNotFoundException
     */
    public function articleDetailsAction($id){
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(array('id'=>$id));
        if($article == null)
            throw new ArticleNotFoundException(sprintf(self::ARTICLE_WITH_ID_WAS_NOT_FOUND, $id));

        $article->setComments(array_reverse($article->getComments()->toArray()));
        return $this->render('default/article.html.twig', [
            'article'=>$article,
            'similarArticles'=>$this->articleService->findSimilarArticles($article),
        ]);
    }

    /**
     * @Route("/articles/latest/load-more", name="latest_articles_load_more")
     * @param Request $request
     * @return Response
     */
    public function loadMoreArticlesAction(Request $request)
    {
        $offset = $request->get("offset");
        if ($offset == null || $offset < 0) $offset = 0;
        $articles = $this->articleService->findArticlesForLatestPosts($offset, $this->categoryService->findAllLocalCategories());

        return $this->render("queries/load-more-articles-index-query.html.twig", [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles/{id}/view", name="article_add_view", defaults={"id":null}, methods={"POST"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     */
    public function viewArticle(Request $request, $id){
        $token = $request->get('token');
        if($id == null || !$this->isCsrfTokenValid($id, $token))
            throw new RestFriendlyExceptionImpl(self::INVALID_ARTICLE_TO_VIEW, 200);
        $this->articleService->viewArticle($this->articleService->findOneById($id));
        return new JsonResponse(['message'=>"OK"]);
    }
}