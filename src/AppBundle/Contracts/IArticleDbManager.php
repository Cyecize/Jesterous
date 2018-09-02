<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/23/2018
 * Time: 9:45 AM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\ViewModel\SliderArticlesViewModel;

interface IArticleDbManager
{

    function findOneById(int $id, bool $hidden = false): ?Article;

    function findAll(bool $hidden = false): array;

    function viewArticle(Article $article = null): void;

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

}