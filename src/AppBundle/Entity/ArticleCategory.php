<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;


    /**
     * @var ArticleCategory;
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ArticleCategory", inversedBy="childrenCategories")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parentCategory;


    /**
     * @var ArticleCategory[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ArticleCategory", mappedBy="parentCategory")
     */
    private $childrenCategories;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article", mappedBy="category")
     * @var Article[]
     */
    private $articles;

    public function __construct()
    {
        $this->childrenCategories = new ArrayCollection();
    }

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

    /**
     * @return ArticleCategory[]
     */
    public function getChildrenCategories()
    {
        return $this->childrenCategories;
    }

    /**
     * @param ArrayCollection $childrenCategories
     */
    public function setChildrenCategories(ArrayCollection $childrenCategories): void
    {
        $this->childrenCategories = $childrenCategories;
    }

    /**
     * @return Article[]
     */
    public function getArticles(): PersistentCollection
    {
        return $this->articles;
    }

//    /**
//     * @return Article[]
//     */
//    public function getArticlesRecursive(): array
//    {
//        $res = array();
//
//        foreach ($this->articles as $article)
//            if ($article->getIsVisible())
//                $res[] = $article;
//
//        foreach ($this->childrenCategories as $childrenCategory) {
//            $res = array_merge($res, $childrenCategory->getArticlesRecursive());
//        }
//        return array_unique($res);
//    }

    /**
     * @return ArticleCategory[]
     */
    public function getChildrenCategoriesRecursive(): array
    {
        $res = array();
        foreach ($this->childrenCategories as $childCategory) {
            $res = array_merge($childCategory->getChildrenCategoriesRecursive(), $res);
        }
        $res = array_merge($this->childrenCategories->toArray(), $res);
        return $res;
    }

    /**
     * @param Article $articles
     */
    public function setArticles(Article $articles): void
    {
        $this->articles = $articles;
    }

    public function __toString()
    {
        return $this->categoryName;
    }

}

