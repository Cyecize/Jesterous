<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 7/22/2018
 * Time: 12:26 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleCategory;
use AppBundle\ViewModel\SliderArticlesViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use function PHPSTORM_META\elementType;

class ArticleDbManager implements IArticleDbManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ArticleRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $articleRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->articleRepo = $em->getRepository(Article::class);
    }

    /**
     * @param Article $article
     * @return Article[]
     */
    public function findSimilarArticles(Article $article): array
    {
        $similar = $this->entityManager->getRepository(Article::class)
            ->findBy(array('category' => $article->getCategory()), array(), 3);
        return $similar;
    }

    /**
     * @param Article[] $articles
     * @return SliderArticlesViewModel[]
     */
    public function forgeSliderViewModel(array $articles): array
    {
        $sliderViewModelArr = array();
        $articlesSize = count($articles);


        foreach ($articles as $index => $article) {
            $slide = null;
            if ($index == 0) {
                $slide = new SliderArticlesViewModel();
                $slide->setArticle($article);
                $slide->setSimilarArticles($this->findSimilarArticles($article));
                if ($articlesSize > 1)
                    $slide->setNextArticle($articles[1]);
                else
                    $slide->setNextArticle($article);
            } else if ($index == $articlesSize - 1) {
                $slide = SliderArticlesViewModel::constructorOverload($article, $articles[0], $this->findSimilarArticles($article));
            } else {
                $slide = SliderArticlesViewModel::constructorOverload($article, $articles[$index + 1], $this->findSimilarArticles($article));
            }
            if ($slide != null)
                $sliderViewModelArr[] = $slide;
        }
        return $sliderViewModelArr;
    }

    /**
     * @param ArticleCategory $articleCategory
     * @return Article[]
     */
    function findArticlesByCategory(ArticleCategory $articleCategory): array
    {
        return $this->articleRepo->findBy(array('category'=>$articleCategory));
    }

    /**
     * @param ArticleCategory[] $articleCategories
     * @return Article[]
     */
    function findArticlesByCategories(array $articleCategories): array
    {
        return $this->articleRepo->findBy(array('category'=>$articleCategories));
    }

    /**
     *
     * @param int $offset
     * @return Article[]
     */
    function findArticlesForLatestPosts(int $offset): array
    {
        return $this->articleRepo->findBy(array(), array('dateAdded'=>"DESC"), 3, $offset);
        //TODO change limit from 3 to something
    }
}