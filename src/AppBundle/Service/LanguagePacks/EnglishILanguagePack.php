<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 9:26 PM
 */

namespace AppBundle\Service\LanguagePacks;


use AppBundle\Constants\Config;
use AppBundle\Contracts\ILanguagePack;

class EnglishILanguagePack implements ILanguagePack
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

    public const LATEST_ARTICLES = "Latest Posts";

    public const REPLY = "Reply";

    public const COMMENTS = "Comments";

    public const LOGIN = "Login";

    public const REGISTER = "Register";

    public const POST_COMMENT = "Post Comment";

    public const YOUR_COMMENT = "Your Comment";

    public const LOGOUT = "Logout";

    public const LIKE = "Like";

    public const LIKES = "likes";

    public const LOGIN_TO_LIKE = "login to like";

    public const MORE = "more";

    public const SIMILAR = "Similar";

    public const ABOUT = "About me";

    public const TRENDING_ARTICLES = "What's Trending";

    public const NEXT = "Next";

    public const PREVIOUS = "Previous";

    public const SECTION_IS_EMPTY = "Section is empty";

    public const TAG_NOT_FOUND = "Tag was not found!";

    public const FOLLOW = "Follow";

    public const UNFOLLOW = "Unfollow";

    public const FOLLOWING = "Following";

    public const FOLLOWERS = "Followers";

    public const VIEWS = "Views";

    public const SHARE = "Share";

    public const REMOVE = "Remove";

    public const NOTIFICATIONS = "Notifications";

    public const NO_NOTIFICATIONS = "All Good";

    public const VIEW_FULL_SCREEN = "View full screen";

    public const REMOVE_ALL = "Remove all";

    public const NEW_ARTICLE_FORMAT = "%s added: %s.";

    public const SUCCESSFULLY_UNSUBBED = "Successfully unsubscribed";

    public const MESSAGE_SENT = "Message was sent";

    public const FIELD_IS_EMPTY = "Field is empty";

    public const PROFILE = "Profile";

    public const CHANGE_PASSWORD = "Change password";

    public const REMOVE_ACCOUNT = "Remove account";

    public const EDIT_SUMMARY = "About me";

    public const CHANGE_PROFILE_PICTURE = "Change profile picture";

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
        return sprintf("About %s", $user);
    }

    public function userNotFound(string $username): string
    {
        return sprintf("Username %s was not found!", $username);
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

    public function trendingArticles() : string
    {
        return self::TRENDING_ARTICLES;
    }

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
        return "Invalid username!";
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
        return sprintf("Category with name %s does not exist.",  $catName);
    }

    function more(): string
    {
        return self::MORE;
    }

    function similar(): string
    {
        return self::SIMILAR;
    }

    function about() : string {
        return self::ABOUT;
    }
}