<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 4:43 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\ILanguageDbManager;
use AppBundle\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;

class LanguageDbManager implements ILanguageDbManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\LanguageRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $langRepo;

    /**
     * LanguageDbManager constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->langRepo = $entityManager->getRepository(Language::class);
    }


    /**
     * Creates initial languages
     */
    public function initLanguages(): void
    {
        if ($this->findLangByLocale(Config::COOKIE_NEUTRAL_LANG) == null)
            $this->createLang(Config::COOKIE_NEUTRAL_LANG);
        if ($this->findLangByLocale(Config::COOKIE_BG_LANG) == null)
            $this->createLang(Config::COOKIE_BG_LANG);
        if ($this->findLangByLocale(Config::COOKIE_EN_LANG) == null)
            $this->createLang(Config::COOKIE_EN_LANG);
    }

    /**
     * @param int $id
     * @return Language|null
     */
    public function findLangById(int $id): ?Language
    {
        return $this->langRepo->findOneBy(array('id' => $id));
    }

    /**
     * @param string $locale
     * @return Language|null
     */
    public function findLangByLocale(string $locale): ?Language
    {
        return $this->langRepo->findOneBy(array('localeName' => $locale));
    }

    /**
     * @return Language[]
     */
    public function findAll(): array
    {
        return $this->langRepo->findAll();
    }

    public function findRange(array $langs): array
    {
       return $this->langRepo->findBy(array('localeName'=>$langs));
    }

    private function createLang(string $localeName): void
    {
        $lang = new Language();
        $lang->setLocaleName($localeName);
        $this->entityManager->persist($lang);
        $this->entityManager->flush();
    }
}