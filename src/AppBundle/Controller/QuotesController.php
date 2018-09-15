<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 8:24 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\BindingModel\QuoteBindingModel;
use AppBundle\Contracts\IArticleCategoryDbManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Contracts\IQuoteDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Quote;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Form\CommentType;
use AppBundle\Form\QuoteType;
use AppBundle\Service\ArticleCategoryDbManager;
use AppBundle\Service\ArticleDbManager;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuotesController extends BaseController
{
    private const QUOTE_NOT_FOUND = "Quote not found!";
    private const INVALID_PARAMETERS = "Invalid parameters for creating quote";

    /**
     * @var IQuoteDbManager
     */
    private $quoteService;

    public function __construct(LocalLanguage $language, IQuoteDbManager $quoteDb)
    {
        parent::__construct($language);
        $this->quoteService = $quoteDb;
    }

    /**
     * @Route("/quotes/{id}/like", name="like_quote", defaults={"id":null}, methods={"POST"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function likeQuoteAction(Request $request, $id)
    {
        $token = $request->get('token');
        $result = ['success' => true, 'disliked' => false];
        if (!$this->isCsrfTokenValid('quote', $token) || !$this->isUserLogged())
            $result['success'] = false;
        else {
            if ($this->quoteService->hasLike($this->getUser(), $id)) {
                $this->quoteService->like($this->getUser(), $id, true);
                $result['disliked'] = true;
            } else {
                $this->quoteService->like($this->getUser(), $id);
            }
        }
        escape:
        return new JsonResponse([
            $result
        ]);
    }


    /**
     * @Route("/admin/quotes/create", name="create_quote")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function createQuoteAction(Request $request)
    {
        $bindingModel = new QuoteBindingModel();
        $form = $this->createForm(QuoteType::class, $bindingModel);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto  escape;
            $this->quoteService->createQuote($bindingModel);
            return $this->redirectToRoute('admin_panel', ['info' => 'Quote Created!']);
        }

        escape:
        return $this->render('admin/quotes/add-quote.html.twig', [
            'form1' => $form->createView(),
            'bindingModel' => $bindingModel,
        ]);
    }

    /**
     * @Route("/admin/quotes/edit/{quoteId}", name="edit_quote", defaults={"quoteId"=null}, methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param $quoteId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws RestFriendlyExceptionImpl
     */
    public function editQuoteAction(Request $request, $quoteId)
    {
        $bindingModel = new QuoteBindingModel();
        $form = $this->createForm(QuoteType::class, $bindingModel);
        $form->handleRequest($request);
        $quote = $this->quoteService->findOneById(intval($quoteId));
        if ($quote == null)
            throw new RestFriendlyExceptionImpl(self::QUOTE_NOT_FOUND);
        if (!$form->isSubmitted() || count($this->validate($bindingModel)) > 0)
            throw new RestFriendlyExceptionImpl(self::INVALID_PARAMETERS);
        $this->quoteService->edit($quote, $bindingModel);
        return $this->redirectToRoute('admin_panel', ['info'=>'Quote Edited!']);
    }

    /**
     * @Route("/admin/quotes/view/{quoteId}", name="view_quote_admin", defaults={"quoteId"=null})
     * @Security("has_role('ROLE_ADMIN')")
     * @param $quoteId
     * @return JsonResponse
     * @throws RestFriendlyExceptionImpl
     */
    public function viewQuoteAction($quoteId)
    {
        $quote = $this->quoteService->findOneById(intval($quoteId));
        if ($quote == null)
            throw new RestFriendlyExceptionImpl(self::QUOTE_NOT_FOUND);
        return new JsonResponse(['authorBg' => $quote->getBgAuthorName(), 'authorEn' => $quote->getEnAuthorName(), 'quoteBg' => $quote->getBgQuote(), 'quoteEn' => $quote->getEnQuote()]);
    }

    /**
     * @Route("/admin/quotes/all", name="observe_quotes")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function observeQuotesAction()
    {
        return $this->render('admin/quotes/all-quotes.html.twig', [
            'quotes' => $this->quoteService->findAll(),
        ]);
    }

    /**
     * @Route("/admin/quotes/hide", name="hide_quote", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function hideQuoteAction(Request $request)
    {
        $this->validateToken($request);
        $this->quoteService->hideQuote($this->bindQuoteParam($request));
        return new JsonResponse(['message' => 'Quote hidden!']);
    }

    /**
     * @Route("/admin/quotes/show", name="show_quote", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function showQuoteAction(Request $request)
    {
        $this->validateToken($request);
        $this->quoteService->showQuote($this->bindQuoteParam($request));
        return new JsonResponse(['message' => 'Quote showed!']);
    }


    //PRIVATE
    private function bindQuoteParam(Request $request): Quote
    {
        $quoteId = $request->get('quoteId');
        if ($quoteId == null)
            throw new RestFriendlyExceptionImpl(self::QUOTE_NOT_FOUND);
        $quote = $this->quoteService->findOneById(intval($quoteId));
        if ($quote == null)
            throw new RestFriendlyExceptionImpl(self::QUOTE_NOT_FOUND);
        return $quote;
    }
}