<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/1/2018
 * Time: 8:59 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICommentDbManager;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Exception\CommentException;
use AppBundle\Util\ModelMapper;
use Doctrine\ORM\EntityManagerInterface;

class CommentDbManager implements ICommentDbManager
{
    private const INVALID_ARTICLE = "Invalid article";

    private const INVALID_COMMENT_CONTENT = "Comment fields were not filled properly!";

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var IArticleDbManager
     */
    private  $articleService;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    /**
     * @var \AppBundle\Repository\CommentRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $commentRepo;

    public function __construct(EntityManagerInterface $em, IArticleDbManager $articleDb, ModelMapper $modelMapper)
    {
        $this->entityManager = $em;
        $this->articleService = $articleDb;
        $this->modelMapper = $modelMapper;
        $this->commentRepo = $em->getRepository(Comment::class);
    }

    /**
     * @param CommentBindingModel $bindingModel
     * @param User|null $user
     * @throws CommentException
     */
    function leaveComment(CommentBindingModel $bindingModel, User $user = null)
    {
        $article = $this->articleService->findOneById($bindingModel->getArticleId());
        if ($article == null)
            throw new CommentException(self::INVALID_ARTICLE);
        if (!$this->isEmpty($bindingModel->getCommenterName()) || !$this->isEmpty($bindingModel->getCommenterEmail()) || !$this->isEmpty($bindingModel->getContent()))
            throw new CommentException(self::INVALID_COMMENT_CONTENT);
        $comment = $this->modelMapper->map($bindingModel, Comment::class);
        $comment->setArticle($article);
        if ($user != null)
            $comment->setUser($user);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    /**
     * @param CommentBindingModel $bindingModel
     * @param User $user
     * @throws CommentException
     */
    function leaveReply(CommentBindingModel $bindingModel, User $user)
    {
        $parentComment = $this->findCommentById($bindingModel->getParentCommentId());
        if ($parentComment == null || !$this->isEmpty($bindingModel->getCommenterName()) || !$this->isEmpty($bindingModel->getCommenterEmail()) || !$this->isEmpty($bindingModel->getContent()))
            throw new CommentException(self::INVALID_COMMENT_CONTENT);

        $reply = $this->modelMapper->map($bindingModel, Comment::class);
        $reply->setUser($user);
        $reply->setParentComment($parentComment);

        $this->entityManager->persist($reply);
        $this->entityManager->flush();
    }

    /**
     * @param int $id
     * @return Comment|null
     */
    function findCommentById(int $id): ?Comment
    {
        return $this->commentRepo->findOneBy(array('id' => $id));
    }

    /**
     * @param Comment $comment
     */
    function removeComment(Comment $comment)
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    /**
     * @param null|string $str
     * @return bool
     */
    private function isEmpty(?string $str): bool
    {
        return $str != null && trim($str) != null;
    }
}