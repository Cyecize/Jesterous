<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/1/2018
 * Time: 8:57 PM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\CommentBindingModel;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;

interface ICommentDbManager
{
    function leaveComment(CommentBindingModel $bindingModel, User $user = null);

    function leaveReply(CommentBindingModel $bindingModel, User $user);

    function findCommentById(int $id): ?Comment;

    function removeComment(Comment $comment);
}