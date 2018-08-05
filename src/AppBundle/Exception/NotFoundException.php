<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 8/5/2018
 * Time: 8:22 AM
 */

namespace AppBundle\Exception;


interface NotFoundException
{
    function getMessage()  ;

    function getEntityName() : string ;
}