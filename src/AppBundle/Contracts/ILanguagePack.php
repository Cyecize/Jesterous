<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:22 PM
 */

namespace AppBundle\Contracts;


interface ILanguagePack
{
    function usernameAlreadyTaken(): string;

    function usernameDoesNotExist(): string;

    function passwordIsIncorrect(): string;

    function passwordIsLessThan(int $count): string;

    function passwordIsLessThanLength(): string;

    function invalidEmailAddress(): string;

    function emailAlreadyInUse(): string;

    function invalidUsername(): string;

    function passwordsDoNotMatch(): string;

    function home(): string;

    function blogPosts(): string;

    function contacts(): string;

    function typeToSearch(): string;

    function topArticles(): string;

    function nextArticle(): string;

    function readMore(): string;

    function yourName(): string;

    function yourEmail(): string;

    function yourMessage(): string;

    function sendMessage(): string;

    function loadMore(): string;

    function subscribe(): string;

    function latestArticles(): string;

    function comments(): string;

    function reply(): string;

    function login(): string;

    function register(): string;

    function postComment(): string;

    function yourComment(): string;

    function logout(): string;

    function like(): string;

    function likes(): string;

    function loginToLike(): string;

    function categoryWithNameDoesNotExist(string $catName);

    function more(): string;

}