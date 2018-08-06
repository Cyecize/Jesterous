<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/22/2018
 * Time: 12:26 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Exception\CommentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Util\ModelMapper;
use AppBundle\ViewModel\SliderArticlesViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use function PHPSTORM_META\elementType;

class ArticleDbManager implements IArticleDbManager
{
    private const INVALID_ARTICLE = "Invalid article";

    private const INVALID_COMMENT_CONTENT = "Comment fields were not filled properly!";

    private const MAX_ARTICLES_PER_PAGE = 3;


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $articleRepo;

    /**
     * @var \AppBundle\Repository\CommentRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $commentRepo;

    /**
     * @var IUserDbManager
     */
    private $userDbManager;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    public function __construct(EntityManagerInterface $em, IUserDbManager $userDbManager, ModelMapper $modelMapper)
    {
        $this->entityManager = $em;
        $this->articleRepo = $em->getRepository(Article::class);
        $this->userDbManager = $userDbManager;
        $this->modelMapper = $modelMapper;
        $this->commentRepo = $em->getRepository(Comment::class);
    }

    function findOneById(int $id, bool $hidden = false): ?Article
    {
        if ($hidden)
            return $this->articleRepo->findOneBy(array('id' => $id));
        else
            return $this->articleRepo->findOneBy(array('id' => $id, 'isVisible' => true));
    }

    function findAll(bool $hidden = false): array
    {
        if ($hidden)
            return $this->articleRepo->findAll();
        else
            return $this->articleRepo->findBy(array('isVisible' => true));
    }

    /**
     * @param Article|null $article
     * @throws RestFriendlyExceptionImpl
     */
    function viewArticle(Article $article = null): void
    {
        if($article == null)
            throw new RestFriendlyExceptionImpl(sprintf("Article does not exist"), 404);
        $article->setViews($article->getViews() + 1);
        $this->entityManager->merge($article);
        $this->entityManager->flush();
    }

    /**
     * @param Article $article
     * @param int $limit
     * @return Article[]
     */
    public function findSimilarArticles(Article $article, int $limit = 3): array
    {
        $similar = $this->entityManager->getRepository(Article::class)
            ->findBy(array('category' => $article->getCategory(), 'isVisible' => true), array(), $limit);

        $similar = array_filter($similar, function (Article $e) use ($article) {
            return $e->getId() != $article->getId();
        });
        return $similar;
    }

    /**
     * @param Article[] $articles
     * @return SliderArticlesViewModel[]
     */
    public function forgeSliderViewModel(array $articles): array
    {
        $sliderViewModelArr = array();
        $articlesSize = count($articles);


        foreach ($articles as $index => $article) {
            $slide = null;
            if ($index == 0) {
                $slide = new SliderArticlesViewModel();
                $slide->setArticle($article);
                $slide->setSimilarArticles($this->findSimilarArticles($article));
                if ($articlesSize > 1)
                    $slide->setNextArticle($articles[1]);
                else
                    $slide->setNextArticle($article);
            } else if ($index == $articlesSize - 1) {
                $slide = SliderArticlesViewModel::constructorOverload($article, $articles[0], $this->findSimilarArticles($article));
            } else {
                $slide = SliderArticlesViewModel::constructorOverload($article, $articles[$index + 1], $this->findSimilarArticles($article));
            }
            if ($slide != null)
                $sliderViewModelArr[] = $slide;
        }
        return $sliderViewModelArr;
    }

    /**
     * @param ArticleCategory $articleCategory
     * @param int|null $limit
     * @return Article[]
     */
    function findArticlesByCategory(ArticleCategory $articleCategory, int $limit = null): array
    {
        return $this->articleRepo->findBy(array('category' => $articleCategory, 'isVisible' => true), array('dailyViews' => 'DESC'), $limit);
    }

    /**
     * @param ArticleCategory[] $articleCategories
     * @param int|null $limit
     * @return Article[]
     */
    function findArticlesByCategories(array $articleCategories, int $limit = null): array
    {
        return $this->articleRepo->findBy(array('category' => $articleCategories, 'isVisible' => true), array('dailyViews' => 'DESC'), $limit);
    }

    /**
     * @param int $offset
     * @param ArticleCategory[] $categories
     * @return Article[]
     */
    function findArticlesForLatestPosts(int $offset, array $categories): array
    {
        return $this->articleRepo->findBy(array('isVisible' => true, 'category'=>$categories), array('dateAdded' => "DESC"), self::MAX_ARTICLES_PER_PAGE, $offset);
    }

    /**
     * @param CommentBindingModel $bindingModel
     * @param User|null $user
     * @throws CommentException
     */
    function leaveComment(CommentBindingModel $bindingModel, User $user = null)
    {
        $article = $this->findOneById($bindingModel->getArticleId());
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

    private function isEmpty(?string $str): bool
    {
        return $str != null && trim($str) != null;
    }

    function findCommentById(int $id): ?Comment
    {
        return $this->commentRepo->findOneBy(array('id' => $id));
    }


}