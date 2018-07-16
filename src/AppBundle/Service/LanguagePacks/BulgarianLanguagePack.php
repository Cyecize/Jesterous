<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:24 PM
 */

namespace AppBundle\Service\LanguagePacks;


use AppBundle\Constants\Config;
use AppBundle\Contracts\LanguagePack;

class BulgarianLanguagePack implements LanguagePack
{

    public const HOME = "Начало";

    public const BLOG_POSTS = "Публикации";

    public const CONTACT = "Контакти";

    public const TYPE_TO_SEARCH = "Търсене";

    public const TOP_ARTICLES = "Популяни";

    public const NEXT_ARTICLE = "Сладваща";

    public const READ_MORE = "Прочетете повече";

    public const YOUR_NAME = "Вашето име";

    public const YOUR_EMAIL = "Вашият Email адрес";

    public const YOUR_MESSAGE = "Вашето запитване";

    public const SEND_MESSAGE = "Изпращане";

    public const LOAD_MORE = "Покажи Повече";

    public const SUBSCRIBE = "Абониране";

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

    function home(): string
    {
        return self::HOME;
    }

    function blogPosts(): string
    {
        return self::BLOG_POSTS;
    }

    function contacts(): string
    {
        return self::CONTACT;
    }

    function typeToSearch(): string
    {
        return self::TYPE_TO_SEARCH;
    }

    function topArticles(): string
    {
        return self::TOP_ARTICLES;
    }

    function nextArticle(): string
    {
        return self::NEXT_ARTICLE;
    }

    function readMore(): string
    {
        return self::READ_MORE;
    }

    function yourName(): string
    {
        return self::YOUR_NAME;
    }

    function yourEmail(): string
    {
        return self::YOUR_EMAIL;
    }

    function yourMessage(): string
    {
        return self::YOUR_MESSAGE;
    }

    function sendMessage(): string
    {
        return self::SEND_MESSAGE;
    }

    function loadMore(): string
    {
        return self::LOAD_MORE;
    }

    function passwordIsLessThanLength(): string
    {
        return self::passwordIsLessThan(Config::MINIMUM_PASSWORD_LENGTH);
    }

    function subscribe(): string
    {
        return self::SUBSCRIBE;
    }
}