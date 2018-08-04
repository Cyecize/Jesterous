<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/4/2018
 * Time: 11:23 AM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\IQuoteDbManager;
use AppBundle\Entity\Quote;
use Doctrine\ORM\EntityManagerInterface;

class QuoteDbManager implements IQuoteDbManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $quoteRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->quoteRepo = $em->getRepository(Quote::class);
    }

    function findRandomQuote(): Quote
    {
        $arr = $this->quoteRepo->findAll();
        shuffle($arr);
        return array_pop($arr);
    }

    function findTopQuote(): Quote
    {
        return $this->quoteRepo->findOneBy(array('isVisible' => true), array('likes' => 'DESC'));
    }

    function findAll(): array
    {
        return $this->quoteRepo->findAll();
    }

    function findAllVisibleQuotes(): array
    {
        return $this->quoteRepo->findBy(array('isVisible' => true));
    }
}