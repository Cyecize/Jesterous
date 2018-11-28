<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 11/28/2018
 * Time: 10:58 PM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Contracts\IQuestionDbManager;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Util\ModelMapper;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;
use Doctrine\ORM\EntityManagerInterface;

class QuestionDbManager implements IQuestionDbManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\QuestionRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $questionRepo;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    public function __construct(EntityManagerInterface $em, ModelMapper $modelMapper)
    {
        $this->entityManager = $em;
        $this->questionRepo = $em->getRepository(Question::class);
        $this->modelMapper = $modelMapper;
    }

    function createQuestion(UserFeedbackBindingModel $bindingModel, User $user = null): Question
    {
        $question = $this->modelMapper->map($bindingModel, Question::class);
        $question->setUser($user);
        $this->entityManager->persist($question);
        $this->entityManager->flush();
        return $question;
    }

    function findOneById(int $id): ?Question
    {
        return $this->questionRepo->find($id);
    }

    function findAll(Pageable $pageable): Page
    {
        return $this->questionRepo->findAllPage($pageable);
    }
}