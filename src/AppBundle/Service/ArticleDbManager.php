<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/22/2018
 * Time: 12:26 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\CommentBindingModel;
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
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Exception\CategoryNotFoundException;
use AppBundle\Exception\CommentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Util\ModelMapper;
use AppBundle\ViewModel\SliderArticlesViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use function PHPSTORM_META\elementType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleDbManager implements IArticleDbManager
{
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

    public function __construct(EntityManagerInterface $em, IUserDbManager $userDbManager, ITagDbManager $tagDbManager, ModelMapper $modelMapper, ICategoryDbManager $categoryDbManager, LocalLanguage $localLanguage, IFileManager $fileManager)
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
        $article->setDailyViews($article->getDailyViews() +1);
        $this->entityManager->merge($article);
        $this->entityManager->flush();
    }

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
        foreach ($tags as $tag) $article->addTag($tag);
        $article->setAuthor($this->userDbManager->findOneById($author->getId()));
        $article->setCategory($category);
        $article->setBackgroundImageLink($this->uploadFileToUser($bindingModel->getFile(), $author));
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
        foreach ($tags as $tag) $article->addTag($tag);
        $article->setCategory($category);
        $article->setIsVisible($bindingModel->isVisible());
        if($file != null){
            $this->fileService->removeFile(substr($article->getBackgroundImageLink(), 1));
            $article->setBackgroundImageLink($this->uploadFileToUser($bindingModel->getFile(), $article->getAuthor()));
        }

        $this->entityManager->merge($article);
        $this->entityManager->flush();
        return $article;
    }

    function findAll(bool $hidden = false): array
    {
        if ($hidden)
            return $this->articleRepo->findAll();
        else
            return $this->articleRepo->findBy(array('isVisible' => true));
    }

    /**
     * @param Article $article
     * @param int $limit
     * @return Article[]
     */
    public function findSimilarArticles(Article $article, int $limit = 3): array
    {
        $similar = $this->entityManager->getRepository(Article::class)
            ->findBy(array('category' => $article->getCategory(), 'isVisible' => true), array('dailyViews'=>'DESC'), $limit);

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
        return $this->articleRepo->findBy(array('category' => $articleCategories, 'isVisible' => true), array('dailyViews' => 'DESC', 'id'=>'DESC'), $limit);
    }

    /**
     * @param int $offset
     * @param ArticleCategory[] $categories
     * @return Article[]
     */
    function findArticlesForLatestPosts(int $offset, array $categories): array
    {
        return $this->articleRepo->findBy(array('isVisible' => true, 'category' => $categories), array('dateAdded' => "DESC"), self::MAX_ARTICLES_PER_PAGE, $offset);
    }

    private function uploadFileToUser(UploadedFile $file, User $user) : string {
        $pathToUser = sprintf(Config::USER_FILES_PATH_FORMAT, $user->getUsername());
        $imageName = $this->fileService->uploadFile($file, $pathToUser);
        return "/" . $pathToUser . $imageName;
    }
}