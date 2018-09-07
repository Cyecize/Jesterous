<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/7/2018
 * Time: 11:49 AM
 */

namespace AppBundle\Controller;


use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Entity\ArticleCategory;
use AppBundle\Exception\CategoryNotFoundException;
use AppBundle\Service\LocalLanguage;

use AppBundle\Util\Pageable;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends BaseController
{

    /**
     * @var IArticleDbManager
     */
    private $articleService;

    /**
     * @var ICategoryDbManager
     */
    private $categoryService;

    public function __construct(LocalLanguage $language, IArticleDbManager $articleDbManager, ICategoryDbManager $categoryDbManager)
    {
        parent::__construct($language);
        $this->articleService = $articleDbManager;
        $this->categoryService = $categoryDbManager;
    }


    /**
     * @Route("/search", name="search_everyone")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $searchQuery = $request->get('q');
        if ($searchQuery == null) $searchQuery = "";

        $category = $this->searchCategory($searchQuery);
        if ($category != null)
            return $this->redirectToRoute('category_details', ['catName' => $category->getCategoryName()]);

        $pageable = new Pageable($request);
        return $this->render('default/search-result.html.twig', [
            'searchText' => $searchQuery,
            'page' => $this->articleService->search($searchQuery,$pageable),
        ]);
    }


    private function searchCategory(string $name): ?ArticleCategory
    {
        try {
            return $this->categoryService->findOneByName($name);
        } catch (CategoryNotFoundException $e) {
        }
        return null;
    }
}