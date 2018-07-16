<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:26 PM
 */

namespace AppBundle\Service\LanguagePacks;


use AppBundle\Constants\Config;
use AppBundle\Contracts\LanguagePack;

class EnglishLanguagePack implements LanguagePack
{

    public const HOME = "Home";

    public const BLOG_POSTS = "Blog Posts";

    public const CONTACT = "Contacts";

    public const TYPE_TO_SEARCH = "Type to Search...";

    public const TOP_ARTICLES = "Top Articles";

    public const NEXT_ARTICLE = "NEXT";

    public const READ_MORE = "Read More...";

    public const YOUR_NAME = "Your Name";

    public const YOUR_EMAIL = "Your Email";

    public const YOUR_MESSAGE = "Message";

    public const SEND_MESSAGE = "Send Message";

    public const LOAD_MORE = "Load More";

    public const SUBSCRIBE = "Subscribe";

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