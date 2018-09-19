<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/18/2018
 * Time: 9:00 PM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Entity\Image;
use AppBundle\Entity\User;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;

interface IImageDbManager
{
    /**
     * @param Image $image
     */
    public function removeImage(Image $image): void;

    /**
     * @param ImageBindingModel $bindingModel
     * @param User $owner
     * @return Image
     */
    public function uploadImage(ImageBindingModel $bindingModel, User $owner): Image;

    /**
     * @param int $id
     * @return Image|null
     */
    public function findOneById(int $id): ?Image;

    /**
     * @param User $user
     * @param Pageable $pageable
     * @return Page
     */
    public function findByUser(User $user, Pageable $pageable): Page;

    /**
     * @return Image[]
     */
    public function findAll(): array;
}