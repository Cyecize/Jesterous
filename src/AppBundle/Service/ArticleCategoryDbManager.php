<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/23/2018
 * Time: 8:58 AM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;

class ArticleCategoryDbManager implements IArticleCategoryDbManager
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleCategoryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $categoryRepository;

    /**
     * @var LocalLanguage
     */
    private $localLang;

    function __construct(EntityManagerInterface $em, LocalLanguage $localLanguage)
    {
        $this->entityManager = $em;
        $this->categoryRepository = $em->getRepository(ArticleCategory::class);
        $this->localLang = $localLanguage;
    }

    /**
     * @return ArticleCategory[]
     */
    public function findAllCategories() : array {
        return $this->entityManager->getRepository(ArticleCategory::class)->findAll();
    }

    /**
     * @return ArticleCategory[]
     */
    public function findLocaleCategories() : array {
        $languages = $this->entityManager->getRepository(Language::class)->findBy(array('localeName'=>array($this->localLang->getLocalLang(), Config::COOKIE_NEUTRAL_LANG)));

        return $this->categoryRepository->findBy(array('language'=>$languages));
    }

}
