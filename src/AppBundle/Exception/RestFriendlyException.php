<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/6/2018
 * Time: 1:27 AM
 */

namespace AppBundle\Exception;


interface RestFriendlyException
{
    public function getMessage();
}