<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/25/2018
 * Time: 1:25 PM
 */

namespace AppBundle\ViewModel;


class ArticleAsMessageViewModel
{
    /**
     * @var string
     */
    private $section;

    /**
     * @var array
     */
    private $options;

    public function __construct($section, $options)
    {
        $this->section  = $section;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }
}