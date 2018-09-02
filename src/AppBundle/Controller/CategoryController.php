<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/1/2018
 * Time: 6:31 PM
 */

namespace AppBundle\Controller;

use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Exception\CommentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Form\CommentType;
use AppBundle\Form\ReplyType;
use AppBundle\Service\ArticleCategoryDbManager;
use AppBundle\Service\ArticleDbManager;
use AppBundle\Service\LocalLanguage;
use AppBundle\ViewModel\CategoriesViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends BaseController
{

    /**
     * @var ICategoryDbManager
     */
    private $categoryService;

    public function __construct(LocalLanguage $language, ICategoryDbManager $categoryDbManager)
    {
        parent::__construct($language);
        $this->categoryService = $categoryDbManager;
    }

    /**
     * @Route("/categories", name="categories_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction(){
        $categories = $this->categoryService->findAllLocalCategories();
        $viewModel = new CategoriesViewModel(array_shift($categories), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel'=>$viewModel,
        ));
    }

    /**
     * @Route("/categories/{catName}", name="category_details", defaults={"catName":null})
     * @param $catName
     * @return Response
     */
    public function showCategoriesAction($catName){
        $categories = $this->categoryService->findAllLocalCategories();
        $viewModel = new CategoriesViewModel($this->categoryService->findOneByName($catName), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel'=>$viewModel,
        ));
    }
}