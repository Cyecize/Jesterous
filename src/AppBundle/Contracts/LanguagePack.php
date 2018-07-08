<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:22 PM
 */

namespace AppBundle\Contracts;


interface LanguagePack
{
    function usernameAlreadyTaken(): string;

    function usernameDoesNotExist(): string;

    function passwordIsIncorrect(): string;

    function passwordIsLessThan(int $count): string;

    function invalidEmailAddress(): string;

    function emailAlreadyInUse(): string;

    function invalidUsername(): string;

    function passwordsDoNotMatch() : string ;

}