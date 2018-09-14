<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 4:38 PM
 */

namespace AppBundle\Contracts;


interface IFirstRunManager
{
    /**
     * Creates initial db content like roles, main category, languages
     */
    public function initDb(): void;
}