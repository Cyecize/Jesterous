<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 11:44 PM
 */

namespace AppBundle\Contracts;


interface ILogDbManager
{
    /**
     * @param string $location
     * @param string $message
     */
    public function log(string $location, string $message) : void ;
}