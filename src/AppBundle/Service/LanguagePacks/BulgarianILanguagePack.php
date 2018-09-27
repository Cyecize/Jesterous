<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:24 PM
 */

namespace AppBundle\Service\LanguagePacks;


use AppBundle\Constants\Config;
use AppBundle\Contracts\ILanguagePack;

class BulgarianILanguagePack implements ILanguagePack
{

    public const HOME = "Начало";

    public const BLOG_POSTS = "Публикации";

    public const CONTACT = "Отзиви";

    public const TYPE_TO_SEARCH = "Търсене";

    public const TOP_ARTICLES = "Популярни";

    public const NEXT_ARTICLE = "Сладваща";

    public const READ_MORE = "Прочетете повече";

    public const YOUR_NAME = "Вашето име";

    public const YOUR_EMAIL = "Вашият Email адрес";

    public const YOUR_MESSAGE = "Вашето запитване";

    public const SEND_MESSAGE = "Изпращане";

    public const LOAD_MORE = "Покажи Повече";

    public const SUBSCRIBE = "Абониране";

    public const LATEST_ARTICLES = "Нови публикации";

    public const REPLY = "Отговор";

    public const COMMENTS = "Коментари";

    public const LOGIN = "Вход";

    public const REGISTER = "Регистрация";

    public const POST_COMMENT = "Споделете какво мислете";

    public const YOUR_COMMENT = "Вашия коментар";

    public const LOGOUT = "Изход";

    public const LIKE = "Харесване";

    public const LIKES = "Харесвания";

    public const LOGIN_TO_LIKE = "влезте в профила си, за да харесате";

    public const MORE = "още";

    public const SIMILAR = "Подобни";

    public const ABOUT = "За автора";

    public const TRENDING_ARTICLES = "Пикантни редове";

    public const NEXT = "Следваща";

    public const PREVIOUS = "Предишна";

    public const SECTION_IS_EMPTY = "Страницата е празна";

    public const TAG_NOT_FOUND = "Тагът не беше намерен!";

    public const FOLLOW = "Последване";

    public const UNFOLLOW = "Не следвам";

    public const FOLLOWING = "Следва";

    public const FOLLOWERS = "Последователи";

    public const VIEWS = "Преглеждания";

    public const SHARE = "Сподели";

    public const REMOVE = "Премахване";

    public const NOTIFICATIONS = "Известия";

    public const NO_NOTIFICATIONS = "Няма нови известия";

    public const VIEW_FULL_SCREEN = "Показване на цял екран";

    public const REMOVE_ALL = "Премахване на всички";

    public const NEW_ARTICLE_FORMAT = "%s добави: %s.";

    public const SUCCESSFULLY_UNSUBBED = "Успешно се отписахте.";

    public const MESSAGE_SENT = "Запитването беше изпратено";

    public const FIELD_IS_EMPTY = "Полето е празно";

    public const PROFILE = "Профил";

    public const CHANGE_PASSWORD = "Смяна на парола";

    public const REMOVE_ACCOUNT = "Премахване на профил";

    public const EDIT_SUMMARY = "За мен";

    public const CHANGE_PROFILE_PICTURE = "Промяна на профилна снимка";

    public const  COMMENT_MESSAGE_FORMAT = "%s ви спомена в коментар!";

    public const  TIME_YOU_WILL_SPEND = "Ще похарчите: %s от деня си";

    public const  LESS_THAN_MINUTE = "по-малко от минута";

    public const  ABOUT_N_MINUTES = "около %d минути";

    public const  ABOUT_AN_HOUR = "около един час";

    public const  ABOUT_N_HOURS = "около %d часа";

    public const  ALL = "Всички";

    public const  COMING_SOON = "Очаквайте скоро.";

    public const  SUCCESSFULLY_SUBSCRIBED = "Успешно абониране";

    public const  STARRED_ARTICLES = "Любими постове";

    public function starredArticles(): string
    {
        return self::STARRED_ARTICLES;
    }

    public function successfullySubscribed(): string
    {
       return self::SUCCESSFULLY_SUBSCRIBED;
    }

    public function comingSoon(): string
    {
       return self::COMING_SOON;
    }

    public function all(): string
    {
       return self::ALL;
    }

    function timeYouWillSpendFormat(): string
    {
        return self::TIME_YOU_WILL_SPEND;
    }

    function lessThanAMinute(): string
    {
        return self::LESS_THAN_MINUTE;
    }

    function aboutNMinutesFormat(): string
    {
        return self::ABOUT_N_MINUTES;
    }

    function aboutAnHour(): string
    {
        return self::ABOUT_AN_HOUR;
    }

    function aboutNHoursFormat(): string
    {
        return self::ABOUT_N_HOURS;
    }

    public function commentMessageFormat(): string
    {
        return self::COMMENT_MESSAGE_FORMAT;
    }

    function profile(): string
    {
        return self::PROFILE;
    }

    function changePassword(): string
    {
        return self::CHANGE_PASSWORD;
    }

    function removeAccount(): string
    {
        return self::REMOVE_ACCOUNT;
    }

    function editSummary(): string
    {
        return self::EDIT_SUMMARY;
    }

    function changeProfilePicture(): string
    {
        return self::CHANGE_PROFILE_PICTURE;
    }

    function fieldCannotBeEmpty(): string
    {
        return self::FIELD_IS_EMPTY;
    }

    function messageWasSent(): string
    {
        return self::MESSAGE_SENT;
    }

    public function successfullyUnsubscribed(): string
    {
        return self::SUCCESSFULLY_UNSUBBED;
    }

    public function newArticleFormat(): string
    {
        return self::NEW_ARTICLE_FORMAT;
    }

    function noNotifications(): string
    {
        return self::NO_NOTIFICATIONS;
    }

    function viewFullScreen(): string
    {
        return self::VIEW_FULL_SCREEN;
    }

    function removeAll(): string
    {
        return self::REMOVE_ALL;
    }

    public function remove(): string
    {
        return self::REMOVE;
    }

    public function notifications(): string
    {
        return self::NOTIFICATIONS;
    }

    public function share(): string
    {
        return self::SHARE;
    }

    public function views(): string
    {
        return self::VIEWS;
    }

    public function following(): string
    {
        return self::FOLLOWING;
    }

    public function followers(): string
    {
        return self::FOLLOWERS;
    }

    public function aboutUser(string $user): string
    {
        return sprintf("За %s", $user);
    }

    public function userNotFound(string $username): string
    {
        return sprintf("Потребител с име %s не беше намерен!", $username);
    }

    public function unfollow(): string
    {
        return self::UNFOLLOW;
    }

    public function follow(): string
    {
        return self::FOLLOW;
    }

    public function tagNotFound(): string
    {
        return self::TAG_NOT_FOUND;
    }

    public function sectionIsEmpty(): string
    {
        return self::SECTION_IS_EMPTY;
    }

    public function next(): string
    {
        return self::NEXT;
    }

    public function previous(): string
    {
        return self::PREVIOUS;
    }

    public function trendingArticles(): string
    {
        return self::TRENDING_ARTICLES;
    }

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

    function latestArticles(): string
    {
        return self::LATEST_ARTICLES;
    }

    function comments(): string
    {
        return self::COMMENTS;
    }

    function reply(): string
    {
        return self::REPLY;
    }

    function login(): string
    {
        return self::LOGIN;
    }

    function register(): string
    {
        return self::REGISTER;
    }

    function postComment(): string
    {
        return self::POST_COMMENT;
    }

    function yourComment(): string
    {
        return self::YOUR_COMMENT;
    }

    function logout(): string
    {
        return self::LOGOUT;
    }

    function like(): string
    {
        return self::LIKE;
    }

    function likes(): string
    {
        return self::LIKES;
    }

    function loginToLike(): string
    {
        return self::LOGIN_TO_LIKE;
    }

    function categoryWithNameDoesNotExist(string $catName)
    {
        return sprintf("Категория с име %s не съществува.", $catName);
    }

    function more(): string
    {
        return self::MORE;
    }

    function similar(): string
    {
        return self::SIMILAR;
    }

    function about(): string
    {
        return self::ABOUT;
    }
}