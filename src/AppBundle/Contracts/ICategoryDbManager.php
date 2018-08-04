<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 10:54 PM
 */

namespace AppBundle\Contracts;


use Doctrine\Common\Collections\ArrayCollection;

interface ICategoryDbManager
{
    function findOneByName(string $name);

    function findOneById(int $id);

    function findAll(): array;

    function findAllLocalCategories(): array;
}