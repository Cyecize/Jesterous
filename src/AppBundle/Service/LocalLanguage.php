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
use Symfony\Component\Debug\Exception\UndefinedMethodException;

class LocalLanguage implements LanguagePack
{
    /**
     * @var LanguagePack
     */
    private $languagePack;

    /**
     * @var string
     */
    private $currentLang;

    public function __construct()
    {
        $this->initLang();
    }

    public function getLocalLang(): string
    {
        return strtolower($this->currentLang);
    }

    public function forName(string $funcName): string
    {
        if (method_exists($this->languagePack, $funcName))
            return $this->languagePack->{$funcName}();
        return $funcName;
    }

    private function initLang(): void
    {
        if (!isset($_COOKIE[Config::COOKIE_LANG_NAME])) {
            $this->languagePack = new BulgarianLanguagePack();
            $this->currentLang = Config::COOKIE_BG_LANG;
            setcookie(Config::COOKIE_LANG_NAME, $this->currentLang);
            return;
        }
        $langType = $_COOKIE[Config::COOKIE_LANG_NAME];
        switch (strtolower($langType)) {
            case Config::COOKIE_EN_LANG:
                $this->languagePack = new EnglishLanguagePack();
                break;
            case Config::COOKIE_BG_LANG:
                $this->languagePack = new BulgarianLanguagePack();
                break;
            default:
                $this->languagePack = new BulgarianLanguagePack();
                setcookie(Config::COOKIE_LANG_NAME, Config::COOKIE_BG_LANG);
                $langType = Config::COOKIE_BG_LANG;
                break;
        }
        $this->currentLang = $langType;
    }


    //IMPLEMENTATIONS

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

    function home(): string
    {
        return $this->languagePack->home();
    }

    function blogPosts(): string
    {
        return $this->languagePack->blogPosts();
    }

    function contacts(): string
    {
        return $this->languagePack->contacts();
    }

    function typeToSearch(): string
    {
        return $this->languagePack->typeToSearch();
    }

    function topArticles(): string
    {
        return $this->languagePack->topArticles();
    }

    function nextArticle(): string
    {
        return $this->languagePack->nextArticle();
    }

    function readMore(): string
    {
        return $this->languagePack->readMore();
    }

    function yourName(): string
    {
        return $this->languagePack->yourName();
    }

    function yourEmail(): string
    {
        return $this->languagePack->yourEmail();
    }

    function yourMessage(): string
    {
        return $this->languagePack->yourMessage();
    }

    function sendMessage(): string
    {
        return $this->languagePack->sendMessage();
    }

    function loadMore(): string
    {
        return $this->languagePack->loadMore();
    }

    function passwordIsLessThanLength(): string
    {
        return $this->languagePack->passwordIsLessThanLength();
    }
}