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

    function findOneById(int  $id, bool $hidden = false) : ?Article;

    function findAll(bool $hidden = false) : array ;


    /**
     * @param Article $article
     * @return Article[]
     */
    function findSimilarArticles(Article $article): array;

    /**
     * @param Article[] $articles
     * @return SliderArticlesViewModel[]
     */
    function forgeSliderViewModel(array $articles): array;

    /**
     * @param ArticleCategory $articleCategory
     * @return Article[]
     */
    function findArticlesByCategory(ArticleCategory $articleCategory): array;

    /**
     * @param ArticleCategory[] $articleCategories
     * @return Article[]
     */
    function findArticlesByCategories(array $articleCategories): array;

    /**
     *
     * @param int $offset
     * @return Article[]
     */
    function findArticlesForLatestPosts(int $offset): array;

    function leaveComment(CommentBindingModel $bindingModel, User $user = null);

    function leaveReply(CommentBindingModel $bindingModel, User $user);

    function findCommentById(int  $id) : ?Comment;
}