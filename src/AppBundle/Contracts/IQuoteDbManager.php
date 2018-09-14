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

    /**
     * @param User $user
     * @param int $quoteId
     * @param bool $dislike
     */
    function like(User $user, int $quoteId, bool $dislike = false): void;

    /**
     * @param User $user
     * @param int $quoteId
     * @return bool
     */
    function hasLike(User $user, int $quoteId): bool;

    /**
     * @return Quote|null
     */
    function findRandomQuote(): ?Quote;

    /**
     * @return Quote|null
     */
    function findTopQuote(): ?Quote;


    /**
     * @param int $id
     * @return Quote|null
     */
    function findOneById(int $id): ?Quote;


    /**
     * @return array
     */
    function findAll(): array;

    /**
     * @return Quote[]
     */
    function findAllVisibleQuotes(): array;

}