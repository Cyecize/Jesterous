<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/4/2018
 * Time: 11:19 AM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\Quote;

interface IQuoteDbManager
{
    function findRandomQuote(): Quote;

    function findTopQuote(): Quote;

    function findAll(): array;

    function findAllVisibleQuotes(): array;
}