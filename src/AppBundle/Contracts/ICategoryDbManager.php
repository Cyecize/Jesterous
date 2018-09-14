<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 10:54 PM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\CreateCategoryBindingModel;
use AppBundle\Entity\ArticleCategory;
use Doctrine\Common\Collections\ArrayCollection;

interface ICategoryDbManager
{

    function initCategories() : void ;

    /**
     * @param CreateCategoryBindingModel $bindingModel
     * @return ArticleCategory
     */
    function createCategory(CreateCategoryBindingModel $bindingModel) : ArticleCategory;

    /**
     * @param string $name
     * @return ArticleCategory|null
     */
    function findOneByName(string $name) : ?ArticleCategory;

    /**
     * @param int $id
     * @return ArticleCategory|null
     */
    function findOneById(int $id) : ?ArticleCategory;

    /**
     * @return ArticleCategory[]
     */
    function findAll(): array;

    /**
     * @return ArticleCategory[]
     */
    function findAllLocalCategories(): array;
}