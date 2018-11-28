<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 11/28/2018
 * Time: 11:12 PM
 */

namespace AppBundle\Controller;

use AppBundle\Contracts\IQuestionDbManager;
use AppBundle\Service\LocalLanguage;
use AppBundle\Util\Pageable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionsController extends BaseController
{
    private const INVALID_QUESTION_ID = "Invalid Question Id";

    /**
     * @var IQuestionDbManager
     */
    private $questionService;

    public function __construct(LocalLanguage $language, IQuestionDbManager $questionDbManager)
    {
        parent::__construct($language);
        $this->questionService = $questionDbManager;
    }

    /**
     * @Route("/admin/questions/observe", name="show_all_questions")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allQuestionsAction(Request $request)
    {
        return $this->render('admin/questions/browse-questions.html.twig', [
            'questionsPage' => $this->questionService->findAll(new Pageable($request)),
        ]);
    }

    /**
     * @Route("/admin/questions/observe/{questionId}", name="show_question_details", defaults={"questionId":0})
     * @Security("has_role('ROLE_ADMIN')")
     * @param $questionId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function questionDetailsAction($questionId)
    {
        $question = $this->questionService->findOneById(intval($questionId));
        if ($question == null)
            throw new NotFoundHttpException(self::INVALID_QUESTION_ID);

        return $this->render('admin/questions/question-details.html.twig', [
            'question'=>$question
        ]);
    }
}