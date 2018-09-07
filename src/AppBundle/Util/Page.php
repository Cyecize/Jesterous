<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/7/2018
 * Time: 6:23 PM
 */

namespace AppBundle\Util;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Page
{

    /**
     * @var int
     */
    private $pages;

    /**
     * @var int
     */
    private $itemsCount;

    /**
     * @var Pageable
     */
    private $pageable;

    /**
     * @var array
     */
    private $elements;

    /**
     * @var Paginator
     */
    private $paginator;

    public function __construct(QueryBuilder $query, Pageable $pageable)
    {
        $this->pageable = $pageable;
        $this->paginator = new Paginator($query);
        $this->init();
    }

    private function init(){
        $this->itemsCount = $this->paginator->count();
        $this->pages = ceil($this->itemsCount / $this->pageable->getSize());
        $this->paginator
            ->getQuery()
            ->setFirstResult($this->pageable->getSize() * ($this->pageable->getPage()-1)) // set the offset
            ->setMaxResults($this->pageable->getSize()); // set the limit
        $this->elements = $this->paginator->getIterator()->getArrayCopy();
    }

    /**
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        return $this->itemsCount;
    }

    /**
     * @return Pageable
     */
    public function getPageable(): Pageable
    {
        return $this->pageable;
    }

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

}