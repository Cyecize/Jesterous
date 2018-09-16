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
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\Quote;
use AppBundle\Entity\User;

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

    /**
     * @var IUserDbManager
     */
    private $userDbManager;

    public function __construct(IQuoteDbManager $quoteDb, IArticleDbManager $articleDbManager, ICategoryDbManager $categoryDbManager, IUserDbManager $userDbManager)
    {
        $this->quoteDbManager = $quoteDb;
        $this->articleDbManager = $articleDbManager;
        $this->categoryDbManager = $categoryDbManager;
        $this->userDbManager = $userDbManager;
    }

    public function getWebsiteName() : string {
        return "JEsterous";
    }

    public function passwordMinLength() : int{
        return Config::MINIMUM_PASSWORD_LENGTH;
    }

    public function findQuote() : ?Quote{
        return $this->quoteDbManager->findRandomQuote();
    }

    public function findPopularArticlesForSidebar() : array {
        $cats = $this->categoryDbManager->findAllLocalCategories();
        return $this->articleDbManager->findArticlesByCategories($cats, 15);
    }

    public function simpleDateFormat(){
        return Config::SIMPLE_DATE_FORMAT;
    }

    public function randomNumber(int $min, int $max, int $exclude) : int {
        $num = rand($min, $max);
        while ($num == $exclude) $num = rand($min, $max);
        return $num;
    }

    public function isUserFollowing(User $cand, User $celeb){
        return $this->userDbManager->isUserFollowing($cand, $celeb);
    }

    public function appId() : string {
        return Config::FB_APP_ID;
    }

}