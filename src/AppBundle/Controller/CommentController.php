<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/1/2018
 * Time: 6:24 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Contracts\ICommentDbManager;
use AppBundle\Contracts\INotificationSenderManager;
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

class CommentController extends BaseController
{
    private const ERROR_POSTING_COMMENT = "Error while posting comment!";
    private const ERROR_LEAVING_REPLY = "Error while posting reply!";
    private const ERROR_REMOVING_COMMENT = "Error while removing comment!";

    /**
     * @var ICommentDbManager
     */
    private $commentService;

    public function __construct(LocalLanguage $language, ICommentDbManager $commentDbManager)
    {
        parent::__construct($language);
        $this->commentService = $commentDbManager;
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
                throw new CommentException(self::ERROR_LEAVING_REPLY);
            $this->commentService->leaveReply($bindingModel, $this->getUser());
            return $this->redirect(trim($bindingModel->getRedirect()));
        }
        return $this->redirect("/");
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
                throw new CommentException(self::ERROR_POSTING_COMMENT);
            $this->commentService->leaveComment($commentBindingModel, $this->getUser());
            return $this->redirect(trim($commentBindingModel->getRedirect()));
        }
        return $this->redirect('/');
    }

    /**
     * @Route("/comments/remove/{id}", name="remove_comment", methods={"POST"}, defaults={"id":null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     */
    public function removeCommentAction(Request $request, $id){
        $token = $request->get('token');
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(array('id'=>$request->get('articleId')));
        $comment = $this->getDoctrine()->getRepository(Comment::class)->findOneBy(array('id'=>$id));
        if($comment == null || $article == null || !$this->isCsrfTokenValid($id, $token))
            throw new RestFriendlyExceptionImpl(self::ERROR_REMOVING_COMMENT, 500);
        $commenter = $comment->getUser();
        $loggedUser = $this->getUser();
        $author = $article->getAuthor();
        if($commenter == null){
            if($loggedUser->getId() != $author->getId())
                throw new RestFriendlyExceptionImpl(self::ERROR_REMOVING_COMMENT, 500);
        }
        else {
            if ($commenter->getId() != $loggedUser->getId() && $loggedUser->getId() != $author->getId())
                throw new RestFriendlyExceptionImpl(self::ERROR_REMOVING_COMMENT, 500);
        }
        $this->commentService->removeComment($comment);
        return new JsonResponse("Comment: " . $id . " removed");
    }
}