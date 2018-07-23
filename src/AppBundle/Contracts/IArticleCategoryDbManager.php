<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/23/2018
 * Time: 9:50 AM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\ArticleCategory;

interface IArticleCategoryDbManager
{
    /**
     * @return ArticleCategory[]
     */
    public function findAllCategories(): array;

    /**
     * @return ArticleCategory[]
     */
    public function findLocaleCategories(): array;
}