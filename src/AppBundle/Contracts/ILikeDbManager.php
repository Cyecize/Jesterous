<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/4/2018
 * Time: 8:15 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\LikeReaction;

interface ILikeDbManager
{
    function createLike(LikeReaction $likeReaction) : LikeReaction;

    function findOneById(int $id) ;

    function remove(LikeReaction $likeReaction);
}