<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:27 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\ILanguagePack;
use AppBundle\Entity\Language;
use AppBundle\Service\LanguagePacks\BulgarianILanguagePack;
use AppBundle\Service\LanguagePacks\EnglishILanguagePack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Debug\Exception\UndefinedMethodException;

class LocalLanguage implements ILanguagePack
{

    private const COOKIE_EXPIRE = 7200; //2HR

    /**
     * @var ILanguagePack
     */
    private $languagePack;

    /**
     * @var string
     */
    private $currentLang;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->initLang();
    }

    public function findCurrentLangs(): array
    {
        return $this->entityManager->getRepository(Language::class)
            ->findBy(array('localeName' => array(Config::COOKIE_NEUTRAL_LANG, $this->currentLang)));
    }

    public function getLocalLang(): string
    {
        return strtolower($this->currentLang);
    }

    public function findLanguageByName(string  $langName) : ?Language{
        return $this->entityManager->getRepository(Language::class)->findOneBy(array('localeName'=>$langName));
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
            $this->languagePack = new BulgarianILanguagePack();
            $this->currentLang = Config::COOKIE_BG_LANG;
            setcookie(Config::COOKIE_LANG_NAME, $this->currentLang, time() + self::COOKIE_EXPIRE, '/');
            return;
        }
        $langType = $_COOKIE[Config::COOKIE_LANG_NAME];
        switch (strtolower($langType)) {
            case Config::COOKIE_EN_LANG:
                $this->languagePack = new EnglishILanguagePack();
                break;
            case Config::COOKIE_BG_LANG:
                $this->languagePack = new BulgarianILanguagePack();
                break;
            default:
                $this->languagePack = new BulgarianILanguagePack();
                setcookie(Config::COOKIE_LANG_NAME, Config::COOKIE_BG_LANG);
                $langType = Config::COOKIE_BG_LANG;
                break;
        }
        $this->currentLang = $langType;
    }


    //IMPLEMENTATIONS

    public function sectionIsEmpty(): string
    {
        return $this->languagePack->sectionIsEmpty();
    }

    public function next(): string
    {
        return $this->languagePack->next();
    }

    public function previous(): string
    {
        return $this->languagePack->previous();
    }

    public function trendingArticles(): string
    {
        return $this->languagePack->trendingArticles();
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

    function subscribe(): string
    {
        return $this->languagePack->subscribe();
    }

    function latestArticles(): string
    {
        return $this->languagePack->latestArticles();
    }

    function comments(): string
    {
        return $this->languagePack->comments();
    }

    function reply(): string
    {
        return $this->languagePack->reply();
    }

    function login(): string
    {
        return $this->languagePack->login();
    }

    function register(): string
    {
        return $this->languagePack->register();
    }

    function postComment(): string
    {
        return $this->languagePack->postComment();
    }

    function yourComment(): string
    {
        return $this->languagePack->yourComment();
    }

    function logout(): string
    {
        return $this->languagePack->logout();
    }

    function like(): string
    {
        return $this->languagePack->like();
    }

    function likes(): string
    {
        return $this->languagePack->likes();
    }

    function loginToLike(): string
    {
        return $this->languagePack->loginToLike();
    }

    function categoryWithNameDoesNotExist(string $catName)
    {
        return $this->languagePack->categoryWithNameDoesNotExist($catName);
    }

    function more(): string
    {
        return $this->languagePack->more();
    }

    function similar(): string
    {
        return $this->languagePack->similar();
    }

    function about(): string
    {
        return $this->languagePack->about();
    }
}