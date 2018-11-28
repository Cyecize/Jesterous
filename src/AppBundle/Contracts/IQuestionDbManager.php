<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 11/28/2018
 * Time: 10:56 PM
 */

namespace AppBundle\Contracts;

use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;

interface IQuestionDbManager
{
    /**
     * @param UserFeedbackBindingModel $bindingModel
     * @param User|null $user
     * @return Question
     */
    function createQuestion(UserFeedbackBindingModel $bindingModel, User $user = null): Question;

    /**
     * @param int $id
     * @return Question|null
     */
    function findOneById(int $id): ?Question;

    /**
     * @param Pageable $pageable
     * @return Page
     */
    function findAll(Pageable $pageable): Page;
}