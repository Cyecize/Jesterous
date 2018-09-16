<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/1/2018
 * Time: 6:31 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\CreateCategoryBindingModel;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Entity\Language;
use AppBundle\Exception\CategoryNotFoundException;
use AppBundle\Form\CreateCategoryType;
use AppBundle\Service\LocalLanguage;
use AppBundle\Util\Pageable;
use AppBundle\ViewModel\CategoriesViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends BaseController
{
    private const CATEGORY_WAS_CREATED = "Category was created!";
    private const CATEGORY_NAME_TAKEN = "Category with that name already exists!";

    /**
     * @var ICategoryDbManager
     */
    private $categoryService;

    /**
     * @var IArticleDbManager
     */
    private $articleService;

    public function __construct(LocalLanguage $language, ICategoryDbManager $categoryDbManager, IArticleDbManager $articleDbManager)
    {
        parent::__construct($language);
        $this->categoryService = $categoryDbManager;
        $this->articleService = $articleDbManager;
    }

    /**
     * @Route("/categories/create", name="create_category")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function createCategoryAction(Request $request)
    {
        $bindingModel = new CreateCategoryBindingModel();
        $form = $this->createForm(CreateCategoryType::class, $bindingModel);
        $form->handleRequest($request);
        $error = null;

        if ($form->isSubmitted()) {
            if (count($this->validate($bindingModel)) > 0)
                goto escape;
            try {
                $this->categoryService->findOneByName($bindingModel->getCategoryName());
                $error = self::CATEGORY_NAME_TAKEN;
                goto escape;
            } catch (CategoryNotFoundException $e) {
            }
            $this->categoryService->createCategory($bindingModel);
            return $this->redirectToRoute('admin_panel', ['info' => self::CATEGORY_WAS_CREATED]);
        }
        escape:
        return $this->render('admin/categories/create-category.html.twig',
            [
                'languages' => $this->getDoctrine()->getRepository(Language::class)->findAll(),
                'form1' => $form->createView(),
                'model' => $bindingModel,
                'error' => $error,
            ]);
    }

    /**
     * @Route("/categories", name="categories_page")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction(Request $request)
    {
        $categories = $this->categoryService->findAllLocalCategories();
        $thisCat = array_shift($categories);
        $viewModel = new CategoriesViewModel($thisCat, $categories, $this->articleService->findArticlesByCategory(array_shift($categories), new Pageable($request)));

        return $this->render("default/categories.html.twig", array(
            'viewModel' => $viewModel,
        ));
    }

    /**
     * @Route("/categories/{catName}", name="category_details", defaults={"catName":null})
     * @param Request $request
     * @param $catName
     * @return Response
     */
    public function showCategoriesAction(Request $request, $catName)
    {
        $categories = $this->categoryService->findAllLocalCategories();
        $cat = $this->categoryService->findOneByName($catName);
        $viewModel = new CategoriesViewModel($cat, $categories, $this->articleService->findArticlesByCategory($cat, new Pageable($request)));

        return $this->render("default/categories.html.twig", array(
            'viewModel' => $viewModel,
        ));
    }

    /**
     * @Route("/categories/{catName}/load-more", name="categories_page_load_more_articles", defaults={"catName":"All"})
     * @param Request $request
     * @param $catName
     * @return Response
     */
    public function loadMoreArticlesAction(Request $request, $catName){
        return $this->render('queries/load-more-articles-query.html.twig', [
            'articles'=>$this->articleService->findArticlesByCategory($this->categoryService->findOneByName($catName), new Pageable($request)),
        ]);
    }
}