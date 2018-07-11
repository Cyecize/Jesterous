<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleCategory
 *
 * @ORM\Table(name="article_categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleCategoryRepository")
 */
class ArticleCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=50, unique=true)
     */
    private $categoryName;

    /**
     * @var Language
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Language", fetch="EAGER")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;


    /**
     * @var ArticleCategory;
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ArticleCategory")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parentCategory;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return ArticleCategory
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    /**
     * @return ArticleCategory
     */
    public function getParentCategory(): ArticleCategory
    {
        return $this->parentCategory;
    }

    /**
     * @param ArticleCategory $parentCategory
     */
    public function setParentCategory(ArticleCategory $parentCategory): void
    {
        $this->parentCategory = $parentCategory;
    }



}

