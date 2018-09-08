<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/8/2018
 * Time: 8:33 AM
 */

namespace AppBundle\Controller;


use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\ITagDbManager;
use AppBundle\Exception\TagNotFoundException;
use AppBundle\Service\LocalLanguage;
use AppBundle\Util\Pageable;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TagController extends BaseController
{
    /**
     * @var ITagDbManager
     */
    private $tagService;

    /**
     * @var IArticleDbManager
     */
    private $articleService;

    public function __construct(LocalLanguage $language, ITagDbManager $tagDbManager, IArticleDbManager $articleDbManager)
    {
        parent::__construct($language);
        $this->tagService = $tagDbManager;
        $this->articleService = $articleDbManager;
    }

    /**
     * @Route("/tags/{tagName}", name="tag_details", defaults={"tagName":null})
     * @param Request $request
     * @param $tagName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws TagNotFoundException
     */
    public function tagDetailsAction(Request $request, $tagName)
    {
        $tag = $this->tagService->findByName($tagName);
        if ($tag == null) throw new TagNotFoundException($this->language->tagNotFound());
        return $this->render('default/tags-result.html.twig',
            [
                'tagName' => $tagName,
                'tagsPage' => $this->articleService->findByTag($tag, new Pageable($request)),
            ]);
    }
}