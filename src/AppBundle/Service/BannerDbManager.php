<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/20/2018
 * Time: 1:55 PM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Contracts\IBannerDbManager;
use AppBundle\Contracts\IFileManager;
use AppBundle\Entity\Banner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BannerDbManager implements IBannerDbManager
{
    private const BANNER_DEFAULT = "/images/category.jpg";

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var IFileManager
     */
    private $fileService;

    /**
     * @var \AppBundle\Repository\BannerRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $bannerRepo;

    /**
     * BannerDbManager constructor.
     * @param $entityManager
     * @param $fileService
     */
    public function __construct(EntityManagerInterface $entityManager, IFileManager $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
        $this->bannerRepo = $entityManager->getRepository(Banner::class);
    }

    /**
     * @param Banner $banner
     */
    public function removeBanner(Banner $banner): void
    {
        $this->fileService->removeFile(substr($banner->getImageLink(), 1));
        $this->entityManager->remove($banner);
        $this->entityManager->flush();
    }

    /**
     * @param ImageBindingModel $bindingModel
     * @return Banner
     */
    public function uploadBanner(ImageBindingModel $bindingModel): Banner
    {
        $banner = new Banner();
        $banner->setImageLink("/" . Config::BANNERS_PATH . $this->fileService->uploadFile($bindingModel->getFile(), Config::BANNERS_PATH));
        $this->entityManager->persist($banner);
        $this->entityManager->flush();
        return $banner;
    }

    /**
     * @param Banner $banner
     * @param int $index
     * @return Banner
     */
    public function editBannerIndex(Banner $banner, int $index): Banner
    {
        $banner->setOrderIndex($index);
        $this->entityManager->merge($banner);
        $this->entityManager->flush();
        return $banner;
    }

    /**
     * @return Banner
     */
    public function findTopBanner(): Banner
    {
        $banner = $this->bannerRepo->findOneBy(array(), array('orderIndex' => 'DESC', 'id'=>'DESC'));
        if ($banner == null) {
            $banner = new Banner();
            $banner->setImageLink(self::BANNER_DEFAULT);
        }
        return $banner;
    }

    /**
     * @param int $id
     * @return Banner|null
     */
    public function findOneById(int $id): ?Banner
    {
        return $this->bannerRepo->findOneBy(array('id' => $id));
    }

    /**
     * @return Banner[]
     */
    public function findAll(): array
    {
        return $this->bannerRepo->findBy(array(), array('orderIndex' => 'DESC'));
    }
}