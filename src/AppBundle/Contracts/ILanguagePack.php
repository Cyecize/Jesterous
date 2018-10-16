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

    function similar(): string;

    function about(): string;

    function trendingArticles(): string;

    function previous(): string;

    function next(): string;

    function sectionIsEmpty(): string;

    function tagNotFound(): string;

    function follow(): string;

    function unfollow(): string;

    function userNotFound(string $username): string;

    function aboutUser(string $user): string;

    function followers(): string;

    function following(): string;

    function views(): string;

    function share(): string;

    function notifications(): string;

    function remove(): string;

    function noNotifications(): string;

    function viewFullScreen(): string;

    function removeAll(): string;

    function newArticleFormat(): string;

    function successfullyUnsubscribed(): string;

    function fieldCannotBeEmpty(): string;

    function messageWasSent(): string;

    function profile(): string;

    function changePassword(): string;

    function removeAccount(): string;

    function editSummary(): string;

    function changeProfilePicture(): string;

    function commentMessageFormat(): string;

    function timeYouWillSpendFormat(): string;

    function lessThanAMinute(): string;

    function aboutNMinutesFormat(): string;

    function aboutAnHour(): string;

    function aboutNHoursFormat() : string ;

    function all() : string ;

    function comingSoon() : string ;

    function successfullySubscribed() : string ;

    function starredArticles() : string ;

    function forgottenPassword() : string ;

    function websiteName() : string ;

    function nickname() : string ;

    function fieldTooLong() : string ;

    function save() : string ;

}