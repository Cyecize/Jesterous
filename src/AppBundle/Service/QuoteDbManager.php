<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/4/2018
 * Time: 11:23 AM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\QuoteBindingModel;
use AppBundle\Contracts\ILikeDbManager;
use AppBundle\Contracts\IQuoteDbManager;
use AppBundle\Entity\LikeReaction;
use AppBundle\Entity\Quote;
use AppBundle\Entity\User;
use AppBundle\Util\ModelMapper;
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

    /**
     * @var ILikeDbManager
     */
    private $likeDbManager;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    public function __construct(EntityManagerInterface $em, ILikeDbManager $likeDbManager, ModelMapper $modelMapper)
    {
        $this->entityManager = $em;
        $this->quoteRepo = $em->getRepository(Quote::class);
        $this->likeDbManager = $likeDbManager;
        $this->modelMapper = $modelMapper;
    }

    function findRandomQuote(): ?Quote
    {
        $arr = $this->findAllVisible();
        shuffle($arr);
        $quote = array_pop($arr);
        return $quote;
    }

    function findTopQuote(): ?Quote
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

    function like(User $user, int $quoteId, bool $isDislike = false): void
    {
        $quote = $this->findOneById($quoteId);
        if ($isDislike) {
            foreach ($quote->getLikes() as $like) {
                if ($like->getUser()->getId() == $user->getId()) {
                    $this->likeDbManager->remove($like);
                    return;
                }
            }
        } else {
            $like = $this->likeDbManager->createLike(LikeReaction::constructOverload($user));
            $quote->addLike($like);
        }

        $this->entityManager->merge($quote);
        $this->entityManager->flush();
    }

    public function hideQuote(Quote $quote): void
    {
        $quote->setIsVisible(false);
        $this->save($quote);
    }

    public function showQuote(Quote $quote): void
    {
        $quote->setIsVisible(true);
        $this->save($quote);
    }

    public function createQuote(QuoteBindingModel $bindingModel): Quote
    {
        $quote = $this->modelMapper->map($bindingModel, Quote::class);
        $this->entityManager->persist($quote);
        $this->entityManager->flush();
        return $quote;
    }

    public function edit(Quote $quote, QuoteBindingModel $bindingModel): Quote
    {
       $quote = $this->modelMapper->merge($bindingModel, $quote);
       $this->entityManager->merge($quote);
       $this->entityManager->flush();
       return $quote;
    }

    function findOneById(int $id): ?Quote
    {
        return $this->quoteRepo->findOneBy(array('id' => $id));
    }

    function hasLike(User $user, int $quoteId): bool
    {
        $quote = $this->findOneById($quoteId);
        foreach ($quote->getLikes() as $like) {
            if ($user->getId() == $like->getUser()->getId())
                return true;
        }
        return false;
    }

    //PRIVATE LOGIC
    private function save(Quote $quote): Quote
    {
        $this->entityManager->merge($quote);
        $this->entityManager->flush();
        return $quote;
    }

    /**
     * @return Quote[]
     */
    private function findAllVisible() : array {
        return $this->quoteRepo->findBy(array('isVisible'=>true));
    }
}