<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 4:42 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\Language;

interface ILanguageDbManager
{
    /**
     * Creates initial languages
     */
    public function initLanguages(): void;

    /**
     * @param int $id
     * @return Language|null
     */
    public function findLangById(int $id): ?Language;

    /**
     * @param string $locale
     * @return Language|null
     */
    public function findLangByLocale(string $locale): ?Language;

    /**
     * @return Language[]
     */
    public function findAll(): array;

    /**
     * @param array $langs
     * @return Language[]
     */
    public function findRange(array $langs) : array ;
}