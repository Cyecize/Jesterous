<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/27/2018
 * Time: 12:07 PM
 */

namespace AppBundle\Controller;


use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\IStarredArticlesDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\Article;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Service\LocalLanguage;
use AppBundle\Util\Pageable;
use AppBundle\ViewModel\StarredArticlesViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StarredArticlesController extends BaseController
{
    private const  ARTICLE_NOT_FOUND = "Article not found!";
    /**
     * @var IUserDbManager
     */
    private $userService;

    /**
     * @var IStarredArticlesDbManager
     */
    private $starredArticlesService;

    /**
     * @var IArticleDbManager
     */
    private $articleService;

    public function __construct(LocalLanguage $language, IUserDbManager $userDb, IStarredArticlesDbManager $articleDb, IArticleDbManager $articleDbManager)
    {
        parent::__construct($language);
        $this->userService = $userDb;
        $this->starredArticlesService = $articleDb;
        $this->articleService = $articleDbManager;
    }

    /**
     * @Route("/users/articles/starred", name="my_starred_articles")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myStarredArticles(Request $request)
    {
        return $this->render('user/starred-articles.html.twig', [
            'viewModel' => new StarredArticlesViewModel(
                $this->starredArticlesService->findByUser(
                    $this->userService->findOneById($this->getUserId()),
                    new Pageable($request)
                ))
        ]);
    }

    /**
     * @Route("/users/articles/starred-page", name="my_starred_articles_page")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMyStarredArticlesPage(Request $request)
    {
        return $this->render('queries/load-more-articles-query.html.twig', [
            'articles' => $this->starredArticlesService->findByUser($this->userService->findOneById($this->getUserId()), new Pageable($request))
        ]);
    }

    /**
     * @Route("/articles/star/{articleId}", name="add_starred_article", defaults={"articleId"=0}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $articleId
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function addStarredArticle(Request $request, $articleId)
    {
        $this->validateToken($request);
        $this->starredArticlesService->addStarredArticle($this->findArticle($articleId), $this->userService->findOneById($this->getUserId()));
        return new JsonResponse(['message' => "Article Starred!"]);
    }

    /**
     * @Route("/articles/unstar/{articleId}", name="remove_starred_article", defaults={"articleId"=0}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $articleId
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function removeStarredArticle(Request $request, $articleId)
    {
        $this->validateToken($request);
        $this->starredArticlesService->removeStarredArticle($this->findArticle($articleId), $this->userService->findOneById($this->getUserId()));
        return new JsonResponse(['message' => "Article Unstarred!"]);
    }

    private function findArticle(int $articleId): Article
    {
        $article = $this->articleService->findOneById($articleId);
        if ($article == null)
            throw new RestFriendlyExceptionImpl(self::ARTICLE_NOT_FOUND);
        return $article;
    }
}