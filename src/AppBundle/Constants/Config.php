<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 4:50 PM
 */

namespace AppBundle\Constants;


use AppBundle\Util\YamlParser;

class Config
{
    public const DEFAULT_TIMEZONE = "Europe/Sofia";

    public const SIMPLE_DATE_FORMAT = "d M, Y -  H:m a";

    public const MINIMUM_PASSWORD_LENGTH = 6;

    public const COOKIE_BG_LANG = "bg";

    public const COOKIE_EN_LANG = "en";

    public const COOKIE_NEUTRAL_LANG = "neutral";

    public const COOKIE_LANG_NAME = "lang";

    public const USER_FILES_PATH_FORMAT = "files/" . "users/" . "%s/";

    public const USER_FILES_PATH = "files/users/";

    public const BANNERS_PATH = "files/banners/";

    public const MAILER_SENDER_NAME = "Jesterous.net";

    public const TIME_TO_READ_WORD = 0.6;

    public static function getAppId() : string {
        return YamlParser::getFbAppId();
       //return "431697957339837";
    }
}