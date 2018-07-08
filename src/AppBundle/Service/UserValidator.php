<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 5:23 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;

class UserValidator
{
    /**
     * @param string $username
     * @return bool
     */
    public static function isUsernameValid( $username) : bool {
        if ($username == null || $username == "")
            return false;
        $username = trim($username);
        $username = explode(" ", $username);
        return count($username) == 1;
    }

    public static function isPasswordValid($password) : bool {
        if($password == null)
            return false;
       return strlen($password) >= Config::MINIMUM_PASSWORD_LENGTH;
    }
}