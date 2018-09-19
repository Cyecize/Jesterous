<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/18/2018
 * Time: 9:03 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Contracts\IFileManager;
use AppBundle\Contracts\IImageDbManager;
use AppBundle\Entity\Image;
use AppBundle\Entity\User;
use AppBundle\Util\Page;
use AppBundle\Util\Pageable;
use Doctrine\ORM\EntityManagerInterface;

class ImageDbManager implements IImageDbManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ImageRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $imageRepo;

    /**
     * @var IFileManager
     */
    private $fileService;

    /**
     * ImageDbManager constructor.
     * @param $entityManager
     * @param $fileService
     */
    public function __construct(EntityManagerInterface $entityManager, IFileManager $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
        $this->imageRepo = $entityManager->getRepository(Image::class);
    }


    /**
     * @param Image $image
     */
    public function removeImage(Image $image): void
    {
        $this->fileService->removeFile(substr($image->getImageLink(), 1));
        $this->entityManager->remove($image);
        $this->entityManager->flush();
    }

    /**
     * @param ImageBindingModel $bindingModel
     * @param User $owner
     * @return Image
     */
    public function uploadImage(ImageBindingModel $bindingModel, User $owner): Image
    {
        $image = new Image();
        $image->setOwner($owner);
        $image->setImageLink($this->fileService->uploadFileToUser($bindingModel->getFile(), $owner));
        $this->entityManager->persist($image);
        $this->entityManager->flush();
        return $image;
    }

    /**
     * @param int $id
     * @return Image|null
     */
    public function findOneById(int $id): ?Image
    {
        return $this->imageRepo->findOneBy(array('id' => $id));
    }

    /**
     * @param User $user
     * @param Pageable $pageable
     * @return Page
     */
    public function findByUser(User $user, Pageable $pageable): Page
    {
        return $this->imageRepo->findByUser($user, $pageable);
    }

    /**
     * @return Image[]
     */
    public function findAll(): array
    {
        return $this->imageRepo->findAll();
    }
}