<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:26 PM
 */

namespace AppBundle\Service\LanguagePacks;


use AppBundle\Contracts\LanguagePack;

class EnglishLanguagePack implements LanguagePack
{

    public function usernameAlreadyTaken(): string
    {
        return "Username already taken!";
    }

    public function passwordIsIncorrect(): string
    {
        return "Password is incorrect!";
    }

    public function usernameDoesNotExist(): string
    {
        return "Username does not exist!";
    }

    public function passwordIsLessThan(int $count): string
    {
       return "Password is less than " . $count . " characters long!";
    }

    function invalidEmailAddress(): string
    {
       return "Invalid Email address!";
    }

    function emailAlreadyInUse(): string
    {
        return "Email already in use!";
    }

    function invalidUsername(): string
    {
        return  "Invalid username!";
    }

    function passwordsDoNotMatch(): string
    {
        return "Passwords did not match!";
    }
}