<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/6/2018
 * Time: 11:02 AM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class CreateCategoryBindingModel
{
    /**
     * @Assert\NotNull(message="field cannot be empty")
     * @Assert\NotBlank()
     * @Assert\Length(max="50", maxMessage="category name too long")
     */
    private $categoryName;

    /**
     * @Assert\NotNull(message="field cannot be empty")
     * @Assert\NotBlank()
     */
    private $locale;


    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @param mixed $categoryName
     */
    public function setCategoryName($categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

}