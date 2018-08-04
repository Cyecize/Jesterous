<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 8:24 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\IQuoteDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use AppBundle\Service\ArticleCategoryDbManager;
use AppBundle\Service\ArticleDbManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends BaseController
{
    /**
     * @Route("/quotes/{id}/like", name="like_quote", defaults={"id":null}, methods={"POST"})
     * @param Request $request
     * @param $id
     * @param IQuoteDbManager $quoteDbManager
     * @return JsonResponse
     */
    public function likeQuoteAction(Request $request, $id, IQuoteDbManager $quoteDbManager)
    {
        $result =  ['success'=>true, 'disliked'=>false];
        if (!$this->isUserLogged() || !$this->isCsrfTokenValid($id, $request->get('token')))
            $result['success'] = false;
        else{
            if($quoteDbManager->hasLike($this->getUser(), $id)){
                $quoteDbManager->like($this->getUser(), $id, true);
                $result['disliked'] = true;
            }else{
                $quoteDbManager->like($this->getUser(), $id);
            }
        }

        escape:
        return new JsonResponse([
            $result
        ]);
    }
}