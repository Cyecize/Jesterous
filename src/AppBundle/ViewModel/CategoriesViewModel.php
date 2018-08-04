<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 11:06 PM
 */

namespace AppBundle\ViewModel;


use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleCategory;

class CategoriesViewModel
{
    /**
     * @var ArticleCategory
     */
    private $selectedCategory;

    /**
     * @var ArticleCategory[]
     */
    private $categories;


    /**
     * @var Article[]
     */
    private $articles;

    public function __construct(ArticleCategory $category, array $categories)
    {
        $this->categories = $categories;
        $this->selectedCategory = $category;
        $this->articles = $this->selectedCategory->getArticlesRecursive();
    }

    /**
     * @return ArticleCategory
     */
    public function getSelectedCategory(): ArticleCategory
    {
        return $this->selectedCategory;
    }

    /**
     * @return ArticleCategory[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return Article[]
     */
    public function getArticles(): array
    {
        return $this->articles;
    }



}