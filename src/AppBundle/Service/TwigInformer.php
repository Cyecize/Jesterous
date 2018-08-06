<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 6:14 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Contracts\IQuoteDbManager;
use AppBundle\Entity\Quote;

class TwigInformer
{

    /**
     * @var IQuoteDbManager
     */
    private $quoteDbManager;

    /**
     * @var IArticleDbManager
     */
    private $articleDbManager;

    /**
     * @var ICategoryDbManager
     */
    private $categoryDbManager;

    public function __construct(IQuoteDbManager $quoteDb, IArticleDbManager $articleDbManager, ICategoryDbManager $categoryDbManager)
    {
        $this->quoteDbManager = $quoteDb;
        $this->articleDbManager = $articleDbManager;
        $this->categoryDbManager = $categoryDbManager;
    }

    public function getWebsiteName() : string {
        return "JEsterous";
    }

    public function passwordMinLength() : int{
        return Config::MINIMUM_PASSWORD_LENGTH;
    }

    public function findQuote() : Quote{
        return $this->quoteDbManager->findRandomQuote();
    }

    public function findPopularArticlesForSidebar() : array {
        return $this->articleDbManager->findArticlesByCategories($this->categoryDbManager->findAllLocalCategories(), 15);
    }

}