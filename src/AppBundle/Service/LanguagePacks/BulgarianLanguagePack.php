<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:24 PM
 */

namespace AppBundle\Service\LanguagePacks;


use AppBundle\Contracts\LanguagePack;

class BulgarianLanguagePack implements LanguagePack
{


    public function usernameAlreadyTaken(): string
    {
        return "Потребителското име е заето!";
    }

    public function passwordIsIncorrect(): string
    {
       return "Грешна парола!";
    }

    public function usernameDoesNotExist(): string
    {
        return "Потребителското име не съществува";
    }

    public function passwordIsLessThan(int $count): string
    {
        return "Паролата е под " . $count . " знака!";
    }

    function invalidEmailAddress(): string
    {
        return "Невалиден Email адрес!";
    }

    function emailAlreadyInUse(): string
    {
        return "Email адресът е зает!";
    }

    function invalidUsername(): string
    {
        return "Невалидно портребителско име!";
    }

    function passwordsDoNotMatch(): string
    {
       return "Паролите не съвпадат!";
    }
}