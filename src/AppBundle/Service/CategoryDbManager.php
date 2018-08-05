<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 10:55 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Exception\CategoryNotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class CategoryDbManager implements ICategoryDbManager
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $catRepo;

    /**
     * @var LocalLanguage
     */
    private $localLanguage;


    public function __construct(EntityManagerInterface $em, LocalLanguage $localLanguage)
    {
        $this->entityManager = $em;
        $this->catRepo = $em->getRepository(ArticleCategory::class);
        $this->localLanguage = $localLanguage;
    }

    /**
     * @param string $name
     * @return ArticleCategory|null|object
     * @throws CategoryNotFoundException
     */
    function findOneByName(string $name)
    {
        $cat = $this->catRepo->findOneBy(array('categoryName'=>$name));
        if($cat == null)
            throw new CategoryNotFoundException($this->localLanguage->categoryWithNameDoesNotExist($name));
        return $cat;
    }

    function findOneById(int $id)
    {
        return $this->catRepo->findOneBy(array('id'=>$id));
    }

    function findAll(): array
    {
        return $this->catRepo->findAll();
    }

    function findAllLocalCategories(): array
    {
        return $this->catRepo->findBy(array('language'=>$this->localLanguage->findCurrentLangs()));
    }
}