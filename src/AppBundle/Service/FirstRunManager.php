<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 4:40 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\ICategoryDbManager;
use AppBundle\Contracts\IFirstRunManager;
use AppBundle\Contracts\ILanguageDbManager;
use AppBundle\Contracts\IRoleDbManager;

class FirstRunManager implements IFirstRunManager
{
    /**
     * @var ICategoryDbManager
     */
    private $categoryService;

    /**
     * @var IRoleDbManager
     */
    private $roleService;

    /**
     * @var ILanguageDbManager
     */
    private $languageService;

    /**
     * FirstRunManager constructor.
     * @param $categoryService
     * @param $roleService
     * @param $languageService
     */
    public function __construct(ICategoryDbManager $categoryService, IRoleDbManager $roleService, ILanguageDbManager $languageService)
    {
        $this->categoryService = $categoryService;
        $this->roleService = $roleService;
        $this->languageService = $languageService;
    }


    /**
     * Creates initial db content like roles, main category, languages
     */
    public function initDb(): void
    {
       $this->languageService->initLanguages();
       $this->roleService->createRolesIfNotExist();
       $this->categoryService->initCategories();
    }
}