<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/15/2018
 * Time: 1:32 PM
 */

namespace AppBundle\Util;


class TwigUtil
{
    public function errorToArray($error = ""): array
    {
        $str = str_replace('</ul>', '', str_replace('<ul>', '', $error));
        $str = str_replace('<li>', ' ', str_replace('</li>', '', $str));
        return explode(' ', $str);
    }
}