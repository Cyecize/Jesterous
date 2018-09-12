<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/23/2018
 * Time: 9:45 AM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\BindingModel\CreateArticleBindingModel;
use AppBundle\BindingModel\EditArticleBindingModel;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;
use AppBundle\ViewModel\SliderArticlesViewModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface IArticleDbManager
{

    /**
     * @param Article|null $article
     */
    function viewArticle(Article $article = null): void;

    /**
     * @param CreateArticleBindingModel $bindingModel
     * @param User $author
     * @return Article
     */
    function createArticle(CreateArticleBindingModel $bindingModel, User $author) : Article ;

    /**
     * @param Article $article
     * @param EditArticleBindingModel $bindingModel
     * @param UploadedFile|null $file
     * @return Article
     */
    function editArticle(Article $article, EditArticleBindingModel $bindingModel, UploadedFile $file = null) : Article;

    /**
     * @param int $id
     * @param bool $hidden
     * @return Article|null
     */
    function findOneById(int $id, bool $hidden = false): ?Article;

    /**
     * @param bool $hidden
     * @return Article[]
     */
    function findAll(bool $hidden = false): array;

    /**
     * @param User $user
     * @return Article[]
     */
    function findMyArticles(User $user): array ;

    /**
     * @param User $user
     * @return Article[]
     */
    function findUserArticles(User $user) : array ;

    /**
     * @param Article $article
     * @param int $limit
     * @return Article[]
     */
    function findSimilarArticles(Article $article, int $limit = 3): array;

    /**
     * @param Article[] $articles
     * @return SliderArticlesViewModel[]
     */
    function forgeSliderViewModel(array $articles): array;

    /**
     * @param ArticleCategory $articleCategory
     * @param int|null $limit
     * @return Article[]
     */
    function findArticlesByCategory(ArticleCategory $articleCategory, int $limit = null): array;

    /**
     * @param ArticleCategory[] $articleCategories
     * @param int|null $limit
     * @return Article[]
     */
    function findArticlesByCategories(array $articleCategories, int $limit = null): array;

    /**
     *
     * @param int $offset
     * @param ArticleCategory[] $categories
     * @return Article[]
     */
    function findArticlesForLatestPosts(int $offset, array $categories): array;

    /**
     * @param string $searchText
     * @param Pageable $pageable
     * @return Page
     */
    function search(string $searchText, Pageable $pageable) : Page ;

    /**
     * @param Tag $tag
     * @param Pageable $pageable
     * @return Page
     */
    function findByTag(Tag $tag, Pageable $pageable) : Page ;
}