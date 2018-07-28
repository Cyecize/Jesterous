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
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
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
        if ($offset < 0)
            $offset = 0;

        $articles = $articleDbManager->findArticlesForLatestPosts($offset);

        return $this->render("queries/load-more-query.html.twig", [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/articles/comments/leave", name="leave_comment_post", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function leaveCommentAction(Request $request)
    {

        $commentBindingModel = new CommentBindingModel();
        $form = $this->createForm(CommentType::class, $commentBindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //TODO to be replaced with comment service
            $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(array('id' => $commentBindingModel->getArticleId()));
            $user = $this->getUser();


            $comment = new Comment();
            $comment->setArticle($article);
            $comment->setCommenterEmail($commentBindingModel->getCommenterEmail());
            $comment->setCommenterName($commentBindingModel->getCommenterName());
            $comment->setContent($commentBindingModel->getContent());
            if ($user != null)
                $comment->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();


            return $this->redirect(trim($commentBindingModel->getRedirect()));
        }

        return $this->redirect('/');
    }
}