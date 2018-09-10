<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/2/2018
 * Time: 7:46 PM
 */

namespace AppBundle\BindingModel;
use Symfony\Component\Validator\Constraints as Assert;

class CreateArticleBindingModel
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

    private $tags;

    /**
     * @Assert\NotNull(message="Select image")
     * @Assert\File(
     *     maxSize="2M", maxSizeMessage="File size more than 2M",
     *     mimeTypes={"image/png", "mage/jpg", "image/jpeg"},
     *     mimeTypesMessage="Please upload a valid jpg"
     * )
     */
    private $file;

    public function __construct()
    {
        $this->isVisible = false;
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
     * @return mixed
     */
    public function getisVisible()
    {
        return $this->isVisible == null ? false : true;
    }

    /**
     * @param mixed $isVisible
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
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getFile()
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
}