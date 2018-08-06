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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends BaseController
{

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
    public function viewArticleAction($id){
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(array('id'=>$id));
        if($article == null)
            throw new ArticleNotFoundException(sprintf("Article with id %s was not found", $id));

        return $this->render('default/article.html.twig', [
            'article'=>$article,
            'similarArticles'=>$this->articleService->findSimilarArticles($article),
        ]);
    }

    /**
     * @Route("/categories", name="categories_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction(){
        $categories = $this->categoryService->findAllLocalCategories();
        $viewModel = new CategoriesViewModel(array_shift($categories), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel'=>$viewModel,
        ));
    }

    /**
     * @Route("/categories/{catName}", name="category_details", defaults={"catName":null})
     * @param $catName
     * @return Response
     */
    public function showCategoriesAction($catName){
        $categories = $this->categoryService->findAllLocalCategories();
        $viewModel = new CategoriesViewModel($this->categoryService->findOneByName($catName), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel'=>$viewModel,
        ));
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
            throw new RestFriendlyExceptionImpl("Invalid article to view", 200);
        $this->articleService->viewArticle($this->articleService->findOneById($id));
        return new JsonResponse(['message'=>"OK"]);
    }

    /**
     * @Route("/articles/comments/leave", name="leave_comment_post", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws CommentException
     */
    public function leaveCommentAction(Request $request)
    {
        $commentBindingModel = new CommentBindingModel();
        $form = $this->createForm(CommentType::class, $commentBindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $token = $request->get('token');
            if(!$this->isCsrfTokenValid($commentBindingModel->getArticleId(), $token))
                throw new CommentException("Error while posting comment!");
            $this->articleService->leaveComment($commentBindingModel, $this->getUser());
            return $this->redirect(trim($commentBindingModel->getRedirect()));
        }
        return $this->redirect('/');
    }

    /**
     * @Route("/articles/comments/reply", name="leave_reply_post", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws CommentException
     */
    public function leaveReplyAction(Request $request)
    {
        $bindingModel = new CommentBindingModel();
        $form = $this->createForm(ReplyType::class, $bindingModel);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $token = $request->get('token');
            if(!$this->isCsrfTokenValid($bindingModel->getParentCommentId(), $token))
                throw new CommentException("Error while posting reply!");
            $this->articleService->leaveReply($bindingModel, $this->getUser());
            return $this->redirect(trim($bindingModel->getRedirect()));
        }
        return $this->redirect("/");
    }
}