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
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Language;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Exception\CategoryNotFoundException;
use AppBundle\Exception\CommentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Form\CommentType;
use AppBundle\Form\CreateCategoryType;
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
    private const CATEGORY_WAS_CREATED = "Category was created!";
    private const CATEGORY_NAME_TAKEN = "Category with that name already exists!";

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
            if(count($this->validate($bindingModel)) > 0)
                goto escape;
            try{
                $this->categoryService->findOneByName($bindingModel->getCategoryName());
                $error = self::CATEGORY_NAME_TAKEN;
                goto escape;
            }catch (CategoryNotFoundException $e){}
            $this->categoryService->createCategory($bindingModel);
            return $this->redirectToRoute('admin_panel', ['info'=>self::CATEGORY_WAS_CREATED]);
        }
        escape:
        return $this->render('admin/categories/create-category.html.twig',
            [
                'languages' => $this->getDoctrine()->getRepository(Language::class)->findAll(),
                'form1' => $form->createView(),
                'model'=>$bindingModel,
                'error'=>$error,
            ]);
    }

    /**
     * @Route("/categories", name="categories_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction()
    {
        $categories = $this->categoryService->findAllLocalCategories();
        $viewModel = new CategoriesViewModel(array_shift($categories), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel' => $viewModel,
        ));
    }

    /**
     * @Route("/categories/{catName}", name="category_details", defaults={"catName":null})
     * @param $catName
     * @return Response
     */
    public function showCategoriesAction($catName)
    {
        $categories = $this->categoryService->findAllLocalCategories();
        $viewModel = new CategoriesViewModel($this->categoryService->findOneByName($catName), $categories);

        return $this->render("default/categories.html.twig", array(
            'viewModel' => $viewModel,
        ));
    }
}