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
use AppBundle\Exception\CommentException;
use AppBundle\Form\CommentType;
use AppBundle\Form\ReplyType;
use AppBundle\Service\ArticleCategoryDbManager;
use AppBundle\Service\ArticleDbManager;
use AppBundle\Service\LocalLanguage;
use AppBundle\ViewModel\CategoriesViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends BaseController
{

    /**
     * @var IArticleDbManager
     */
    private $articleService;

    public function __construct(LocalLanguage $language, IArticleDbManager $articleDbManager)
    {
        parent::__construct($language);
        $this->articleService = $articleDbManager;
    }

    /**
     * @Route("/categories", name="categories_page")
     * @param ICategoryDbManager $categoryDbManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction(ICategoryDbManager $categoryDbManager){
        $categories = $categoryDbManager->findAllLocalCategories();
        $viewModel = new CategoriesViewModel(array_shift($categories), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel'=>$viewModel,
        ));
    }

    /**
     * @Route("/categories/{catName}", name="category_details", defaults={"catName":null})
     * @param $catName
     * @param ICategoryDbManager $categoryDbManager
     * @return Response
     */
    public function showCategoriesAction($catName, ICategoryDbManager $categoryDbManager){
        $categories = $categoryDbManager->findAllLocalCategories();
        $viewModel = new CategoriesViewModel($categoryDbManager->findOneByName($catName), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel'=>$viewModel,
        ));
    }

    /**
     * @param Request $request
     * @param IArticleDbManager $articleDbManager
     * @return Response
     * @Route("/articles/load-more", name="load_more_articles")
     */
    public function loadMoreArticlesAction(Request $request, IArticleDbManager $articleDbManager)
    {
        $offset = $request->get("offset");
        if ($offset == null)
            $offset = 0;
        if ($offset < 0)
            $offset = 0;

        $articles = $articleDbManager->findArticlesForLatestPosts($offset);

        return $this->render("queries/load-more-articles-index-query.html.twig", [
            'articles' => $articles,
        ]);
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