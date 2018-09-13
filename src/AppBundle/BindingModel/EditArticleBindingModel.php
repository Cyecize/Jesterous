<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/5/2018
 * Time: 9:42 PM
 */

namespace AppBundle\BindingModel;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


class EditArticleBindingModel
{
    /**
     * @Assert\NotNull(message="field is empty")
     * @Assert\NotBlank(message="field is empty")
     * @Assert\Length(max="100", maxMessage="Length exceeded")
     */
    private $title;

    /**
     * @Assert\Length(max="255", maxMessage="Length exceeded")
     */
    private $summary;

    private $mainContent;

    private $isVisible;

    private $categoryId;

    private $stringOfTags;

    private $file;

    private $notify;

    /**
     * @Assert\NotNull(message="Daily views can't be null, mate ==)")
     */
    private $dailyViews;

    public function __construct()
    {
        $this->isVisible = false;
        $this->notify = false;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getMainContent()
    {
        return $this->mainContent;
    }

    /**
     * @param mixed $mainContent
     */
    public function setMainContent($mainContent): void
    {
        $this->mainContent = $mainContent;
    }

    /**
     * @return bool
     */
    public function isVisible() : bool
    {
        return $this->isVisible == null ? false : true;
    }

    /**
     * @param bool $isVisible
     */
    public function setIsVisible($isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getStringOfTags()
    {
        return $this->stringOfTags;
    }

    /**
     * @param mixed $stringOfTags
     */
    public function setStringOfTags($stringOfTags): void
    {
        $this->stringOfTags = $stringOfTags;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile() : ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getDailyViews()
    {
        return $this->dailyViews;
    }

    /**
     * @param mixed $dailyViews
     */
    public function setDailyViews($dailyViews): void
    {
        $this->dailyViews = $dailyViews;
    }


    /**
     * @return bool
     */
    public function isNotify(): bool
    {
        return $this->notify;
    }

    /**
     * @param bool $notify
     */
    public function setNotify(bool $notify): void
    {
        $this->notify = $notify;
    }

}