<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 6:14 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\IQuoteDbManager;
use AppBundle\Entity\Quote;

class TwigInformer
{

    /**
     * @var IQuoteDbManager
     */
    private $quoteDbManager;

    public function __construct(IQuoteDbManager $quoteDb)
    {
        $this->quoteDbManager = $quoteDb;
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

}