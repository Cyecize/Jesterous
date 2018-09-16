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
use AppBundle\Util\Page;

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

    /**
     * @var Page
     */
    private $page;

    public function __construct(ArticleCategory $category, array $categories, Page $page)
    {
        $this->categories = $categories;
        $this->selectedCategory = $category;
        $this->articles = $page->getElements();
        $this->page = $page;
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

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }


}