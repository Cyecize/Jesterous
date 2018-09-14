<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 10:55 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\CreateCategoryBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Entity\Language;
use AppBundle\Exception\CategoryNotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CategoryDbManager implements ICategoryDbManager
{
    private const PARENT_CATEGORY_NAME = "All";
    private const LANG_NOT_FOUND = "Language was not found!";

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
     * @param CreateCategoryBindingModel $bindingModel
     * @return ArticleCategory
     * @throws CategoryNotFoundException
     * @throws InternalErrorException
     */
    public function createCategory(CreateCategoryBindingModel $bindingModel): ArticleCategory
    {
        //$this->initCategories();
        $category = new ArticleCategory();
        $category->setCategoryName($bindingModel->getCategoryName());
        $lang = $this->localLanguage->findLanguageByName($bindingModel->getLocale());
        if ($lang == null)
            throw new InternalErrorException(self::LANG_NOT_FOUND);
        $category->setParentCategory($this->findOneByName(self::PARENT_CATEGORY_NAME));
        $category->setLanguage($lang);
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return $category;
    }

    /**
     * @param string $name
     * @return ArticleCategory|null|object
     * @throws CategoryNotFoundException
     */
    function findOneByName(string $name): ?ArticleCategory
    {
        $cat = $this->catRepo->findOneBy(array('categoryName' => $name));
        if ($cat == null)
            throw new CategoryNotFoundException($this->localLanguage->categoryWithNameDoesNotExist($name));
        return $cat;
    }

    function findOneById(int $id): ?ArticleCategory
    {
        return $this->catRepo->findOneBy(array('id' => $id));
    }

    function findAll(): array
    {
        return $this->catRepo->findAll();
    }

    function findAllLocalCategories(): array
    {
        return $this->catRepo->findBy(array('language' => $this->localLanguage->findCurrentLangs()));
    }

    function initCategories(): void
    {
        try {
            $this->findOneByName(self::PARENT_CATEGORY_NAME);
            return;
        } catch (CategoryNotFoundException $e) {

        }
        $cat = new ArticleCategory();
        $cat->setCategoryName(self::PARENT_CATEGORY_NAME);
        $cat->setLanguage($this->localLanguage->findLanguageByName(Config::COOKIE_NEUTRAL_LANG));
        $this->entityManager->persist($cat);
        $this->entityManager->flush();
    }
}