<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/4/2018
 * Time: 6:52 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\Tag;

interface ITagDbManager
{
    /**
     * @param int $id
     * @return Tag|null
     */
    public function findById(int $id) : ?Tag;

    /**
     * @param string $name
     * @return Tag|null
     */
    public function findByName(string $name) : ?Tag;

    /**
     * @param string $tags
     * @return Tag[]
     */
    public function addTags(string $tags = null) : array ;

    /**
     * @return Tag[]
     */
    public function findAll() : array ;

}