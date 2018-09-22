<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/15/2018
 * Time: 1:32 PM
 */

namespace AppBundle\Util;


use AppBundle\Service\LocalLanguage;

class TwigUtil
{
    private $language;

    /**
     * TwigUtil constructor.
     * @param $language
     */
    public function __construct(LocalLanguage $language)
    {
        $this->language = $language;
    }

    public function errorToArray($error = ""): array
    {
        $str = str_replace('</ul>', '', str_replace('<ul>', '', $error));
        $str = str_replace('<li>', ' ', str_replace('</li>', '', $str));
        return explode(' ', $str);
    }

    public function displayEstimated(float $number): string
    {
        return sprintf($this->language->timeYouWillSpendFormat(), $this->estimate($number));
    }

    private function estimate(float $number): string
    {
        $number = intval(floor($number));
        if ($number < 60)
            return $this->language->lessThanAMinute();
        if ($number < 3600)
            return sprintf($this->language->aboutNMinutesFormat(), floor($number / 60));
        if ($number == 3600)
            return $this->language->aboutAnHour();
        return sprintf($this->language->aboutNHoursFormat(), floor($number / 3600));
    }
}