<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/8/2018
 * Time: 4:50 PM
 */

namespace AppBundle\Constants;


class Config
{
    public const DEFAULT_TIMEZONE = "Europe/Sofia";

    public const SIMPLE_DATE_FORMAT = "D M, Y -  H:m a";

    public const MINIMUM_PASSWORD_LENGTH = 6;

    public const COOKIE_BG_LANG = "bg";

    public const COOKIE_EN_LANG = "en";

    public const COOKIE_NEUTRAL_LANG = "neutral";

    public const COOKIE_LANG_NAME = "lang";

    public const USER_FILES_PATH_FORMAT = "files/" . "users/" . "%s/";

    public const USER_FILES_PATH = "files/users/";

    public const MAILER_SENDER_EMAIL = "ceci_nfs9@abv.bg";

    public const MAILER_SENDER_NAME = "Jesterous.net";
}