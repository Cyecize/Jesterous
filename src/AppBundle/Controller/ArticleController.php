<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/23/2018
 * Time: 10:24 AM
 */

namespace AppBundle\Controller;

use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Entity\Article;
use AppBundle\Service\ArticleCategoryDbManager;
use AppBundle\Service\ArticleDbManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends BaseController
{

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
        if($offset < 0)
            $offset = 0;

        $articles = $articleDbManager->findArticlesForLatestPosts($offset);

        return $this->render("queries/load-more-query.html.twig",[
            'articles'=>$articles,
        ]);
    }
}