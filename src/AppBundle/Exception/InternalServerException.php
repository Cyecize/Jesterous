<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/5/2018
 * Time: 12:26 AM
 */

namespace AppBundle\Exception;


interface InternalServerException
{
    public function getMessage();
}