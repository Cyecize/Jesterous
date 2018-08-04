<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/4/2018
 * Time: 11:19 AM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\Quote;
use AppBundle\Entity\User;

interface IQuoteDbManager
{
    function findRandomQuote(): Quote;

    function findTopQuote(): Quote;

    function findAll(): array;

    function findAllVisibleQuotes(): array;

    function like(User $user, int $quoteId, bool $dislike = false);

    function findOneById(int $id);

    function hasLike(User $user, int $quoteId) : bool ;

}