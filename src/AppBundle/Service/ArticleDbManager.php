<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/22/2018
 * Time: 12:26 PM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\CreateArticleBindingModel;
use AppBundle\BindingModel\EditArticleBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Contracts\IFileManager;
use AppBundle\Contracts\ITagDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use AppBundle\Exception\CategoryNotFoundException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Util\ModelMapper;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;
use AppBundle\ViewModel\SliderArticlesViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleDbManager implements IArticleDbManager
{
    private const MAX_ARTICLES_PER_PAGE = 15;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $articleRepo;

    /**
     * @var IUserDbManager
     */
    private $userDbManager;

    /**
     * @var ITagDbManager
     */
    private $tagService;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    /**
     * @var ICategoryDbManager
     */
    private $categoryService;

    /**
     * @var LocalLanguage
     */
    private $localLanguage;

    /**
     * @var IFileManager
     */
    private $fileService;

    public function __construct(EntityManagerInterface $em,
                                IUserDbManager $userDbManager, ITagDbManager $tagDbManager,
                                ModelMapper $modelMapper, ICategoryDbManager $categoryDbManager,
                                LocalLanguage $localLanguage, IFileManager $fileManager)
    {
        $this->entityManager = $em;
        $this->articleRepo = $em->getRepository(Article::class);
        $this->userDbManager = $userDbManager;
        $this->tagService = $tagDbManager;
        $this->modelMapper = $modelMapper;
        $this->categoryService = $categoryDbManager;
        $this->localLanguage = $localLanguage;
        $this->fileService = $fileManager;
    }

    /**
     * @param Article|null $article
     * @throws RestFriendlyExceptionImpl
     */
    function viewArticle(Article $article = null): void
    {
        if ($article == null)
            throw new RestFriendlyExceptionImpl(sprintf("Article does not exist"), 404);
        $article->setViews($article->getViews() + 1);
        $article->setDailyViews($article->getDailyViews() + 1);
        $this->entityManager->merge($article);
        $this->entityManager->flush();
    }

    /**
     * @param Article $article
     */
    public function saveArticle(Article $article): void
    {
        $this->entityManager->merge($article);
        $this->entityManager->flush();
    }

    /**
     * @param int $id
     * @param bool $hidden
     * @return Article|null
     */
    function findOneById(int $id, bool $hidden = false): ?Article
    {
        if ($hidden)
            return $this->articleRepo->findOneBy(array('id' => $id));
        else
            return $this->articleRepo->findOneBy(array('id' => $id, 'isVisible' => true));
    }

    /**
     * @param CreateArticleBindingModel $bindingModel
     * @param User $author
     * @return Article
     * @throws CategoryNotFoundException
     */
    function createArticle(CreateArticleBindingModel $bindingModel, User $author): Article
    {
        $tags = $this->tagService->addTags($bindingModel->getTags());
        $category = $this->categoryService->findOneById($bindingModel->getCategoryId());
        if ($category == null) throw new CategoryNotFoundException($this->localLanguage->categoryWithNameDoesNotExist($bindingModel->getCategoryId()));

        $article = $this->modelMapper->map($bindingModel, Article::class);
        $article->setTags(new ArrayCollection());
        $article->setEstimatedReadTime($this->estimateReadTime($article->getMainContent()));
        foreach ($tags as $tag) $article->addTag($tag);
        $article->setAuthor($this->userDbManager->findOneById($author->getId()));
        $article->setCategory($category);
        $article->setBackgroundImageLink($this->fileService->uploadFileToUser($bindingModel->getFile(), $author));
        $article->setIsVisible($bindingModel->getisVisible());
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $article;
    }

    /**
     * @param Article $article
     * @param EditArticleBindingModel $bindingModel
     * @param UploadedFile|null $file
     * @return Article
     * @throws CategoryNotFoundException
     */
    function editArticle(Article $article, EditArticleBindingModel $bindingModel, UploadedFile $file = null): Article
    {
        $tags = $this->tagService->addTags($bindingModel->getStringOfTags());
        $category = $this->categoryService->findOneById($bindingModel->getCategoryId());
        if ($category == null) throw new CategoryNotFoundException($this->localLanguage->categoryWithNameDoesNotExist($bindingModel->getCategoryId()));

        $article = $this->modelMapper->merge($bindingModel, $article);
        $article->setTags(new ArrayCollection());
        $article->setEstimatedReadTime($this->estimateReadTime($article->getMainContent()));
        foreach ($tags as $tag) $article->addTag($tag);
        $article->setCategory($category);
        $article->setIsVisible($bindingModel->isVisible());
        if ($file != null) {
            $this->fileService->removeFile(substr($article->getBackgroundImageLink(), 1));
            $article->setBackgroundImageLink($this->fileService->uploadFileToUser($bindingModel->getFile(), $article->getAuthor()));
        }
        $this->saveArticle($article);
        return $article;
    }

    /**
     * @param bool $hidden
     * @return Article[]
     */
    function findAll(bool $hidden = false): array
    {
        if ($hidden)
            return $this->articleRepo->findAll();
        else
            return $this->articleRepo->findBy(array('isVisible' => true));
    }

    /**
     * @param User $user
     * @return Article[]
     */
    public function findMyArticles(User $user): array
    {
        return $this->articleRepo->findBy(array('author' => $user), array('id' => 'DESC'));
    }

    /**
     * @param User $user
     * @return array
     */
    public function findUserArticles(User $user): array
    {
        return $this->articleRepo->findBy(array('author' => $user, 'isVisible' => true), array('id' => 'DESC'));
    }

    /**
     * @param Article $article
     * @param int $limit
     * @return Article[]
     */
    public function findSimilarArticles(Article $article, int $limit = 3): array
    {
        $similar = $this->entityManager->getRepository(Article::class)
            ->findBy(array('category' => $article->getCategory(), 'isVisible' => true), array('dailyViews' => 'DESC'), $limit);

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

    public function searchMyArticles(string $searchText, User $author): array
    {
        return $this->articleRepo->searchMyArticles($searchText, $author);
    }

    /**
     * @param ArticleCategory $articleCategory
     * @param Pageable $pageable
     * @return Page
     */
    function findArticlesByCategory(ArticleCategory $articleCategory, Pageable $pageable): Page
    {
        $cats = $articleCategory->getChildrenCategoriesRecursive();
        $cats[] = $articleCategory;
        return $this->findArticlesByCategories($pageable, $cats);
        //return $this->articleRepo->findBy(array('category' => $articleCategory, 'isVisible' => true), array('dailyViews' => 'DESC'), $limit);
    }

    /**
     * @param Pageable $pageable
     * @param ArticleCategory[] $categories
     * @return Page
     */
    function findArticlesByCategories(Pageable $pageable, array $categories): Page
    {
        //return $this->articleRepo->findBy(array('isVisible' => true, 'category' => $categories), array('dateAdded' => "DESC"), self::MAX_ARTICLES_PER_PAGE, $offset);
        $qb = $this->articleRepo->createQueryBuilder('a');
        $query = $qb
            ->where('a.isVisible = TRUE')
            ->andWhere($qb->expr()->in('a.category', '?1'))
            ->setParameter(1, $categories)
            ->orderBy('a.dailyViews', 'DESC')
            ->addOrderBy('a.id', 'DESC');
        return new Page($query, $pageable);
    }

    /**
     * @param string $searchText
     * @param Pageable $pageable
     * @return Page
     */
    function search(string $searchText, Pageable $pageable): Page
    {
        return $this->articleRepo->searchVisible($searchText, $pageable);
    }

    /**
     * @param Tag $tag
     * @param Pageable $pageable
     * @return Page
     */
    function findByTag(Tag $tag, Pageable $pageable): Page
    {
        $qb = $this->articleRepo->createQueryBuilder('a');
        $query = $qb
            ->where('a.isVisible = TRUE')
            ->andWhere(':tag MEMBER OF a.tags')
            ->setParameter('tag', $tag)
            ->orderBy('a.dailyViews', 'DESC');
        return new Page($query, $pageable);
    }

    //private logic
    private function estimateReadTime(string $content = null): float
    {
        if ($content == null) return 0;
        $arr = preg_split('/[\s,.!?]+/', strip_tags($content));
        return count($arr) * Config::TIME_TO_READ_WORD;
    }

}