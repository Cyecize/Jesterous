<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:27 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\LanguagePack;
use AppBundle\Service\LanguagePacks\BulgarianLanguagePack;
use AppBundle\Service\LanguagePacks\EnglishLanguagePack;

class LocalLanguage implements LanguagePack
{
    /**
     * @var LanguagePack
     */
    private $languagePack;

    public function __construct()
    {
        $this->initLang();
    }

    private function initLang() : void{
        if(!isset($_COOKIE[Config::COOKIE_LANG_NAME])){
            $this->languagePack = new BulgarianLanguagePack();
            setcookie(Config::COOKIE_LANG_NAME, Config::COOKIE_BG_LANG);
            return;
        }
        $langType  = $_COOKIE[Config::COOKIE_LANG_NAME];
        switch (strtolower($langType)){
            case Config::COOKIE_EN_LANG:
                $this->languagePack = new EnglishLanguagePack();
                break;
            case Config::COOKIE_BG_LANG:
                $this->languagePack = new BulgarianLanguagePack();
                break;
            default:
                $this->languagePack = new BulgarianLanguagePack();
                setcookie(Config::COOKIE_LANG_NAME, Config::COOKIE_BG_LANG);
                break;
        }
    }

    public function usernameAlreadyTaken(): string
    {
        return $this->languagePack->usernameAlreadyTaken();
    }

    public function passwordIsIncorrect(): string
    {
        return $this->languagePack->passwordIsIncorrect();
    }

    public function usernameDoesNotExist(): string
    {
        return $this->languagePack->usernameDoesNotExist();
    }

    public function passwordIsLessThan(int $count): string
    {
        return $this->languagePack->passwordIsLessThan($count);
    }

    function invalidEmailAddress(): string
    {
        return $this->languagePack->invalidEmailAddress();
    }

    function emailAlreadyInUse(): string
    {
        return $this->languagePack->emailAlreadyInUse();
    }

    function invalidUsername(): string
    {
       return $this->languagePack->invalidUsername();
    }

    function passwordsDoNotMatch(): string
    {
        return $this->languagePack->passwordsDoNotMatch();
    }
}