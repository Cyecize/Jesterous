<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/27/2018
 * Time: 12:12 PM
 */

namespace AppBundle\ViewModel;


use AppBundle\Entity\Article;
use AppBundle\Util\Page;

class StarredArticlesViewModel
{
    /**
     * @var Page
     */
    private $page;

    /**
     * @var Article[]
     */
    private $articles;

    /**
     * StarredArticlesViewModel constructor.
     * @param $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
        $this->articles = $page->getElements();
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