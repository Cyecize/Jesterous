<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/22/2018
 * Time: 1:29 PM
 */

namespace AppBundle\ViewModel;


use AppBundle\Entity\Article;

class SliderArticlesViewModel
{
    /**
     * @var Article
     */
    private $article;

    /**
     * @var Article
     */
    private $nextArticle;

    /**
     * @var Article[]
     */
    private $similarArticles;

    public function __construct()
    {

    }

    public static function constructorOverload(Article $article, Article $nextArticle, array $similarArticles) : SliderArticlesViewModel{
        $obj = new SliderArticlesViewModel();
        return $obj->setArticle($article)->setNextArticle($nextArticle)->setSimilarArticles($similarArticles);
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return SliderArticlesViewModel
     */
    public function setArticle(Article $article): SliderArticlesViewModel
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @return Article
     */
    public function getNextArticle(): Article
    {
        return $this->nextArticle;
    }

    /**
     * @param Article $nextArticle
     * @return SliderArticlesViewModel
     */
    public function setNextArticle(Article $nextArticle): SliderArticlesViewModel
    {
        $this->nextArticle = $nextArticle;
        return $this;
    }

    /**
     * @return Article[]
     */
    public function getSimilarArticles(): array
    {
        return $this->similarArticles;
    }

    /**
     * @param Article[] $similarArticles
     * @return SliderArticlesViewModel
     */
    public function setSimilarArticles(array $similarArticles):  SliderArticlesViewModel
    {
        $this->similarArticles = $similarArticles;
        return $this;
    }



}