<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 11:44 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Util\Page;
use AppBundle\Util\Pageable;

interface ILogDbManager
{
    /**
     * @param string $location
     * @param string $message
     */
    public function log(string $location, string $message) : void ;

    /**
     * @param Pageable $pageable
     * @return Page
     */
    public function findAll(Pageable $pageable) : Page;
}