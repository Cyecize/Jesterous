<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/20/2018
 * Time: 1:29 PM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Entity\Banner;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface IBannerDbManager
{
    /**
     * @param Banner $banner
     */
    public function removeBanner(Banner $banner): void;

    /**
     * @param ImageBindingModel $bindingModel
     * @return Banner
     */
    public function uploadBanner(ImageBindingModel $bindingModel): Banner;

    /**
     * @param Banner $banner
     * @param int $index
     * @return Banner
     */
    public function editBannerIndex(Banner $banner, int  $index) : Banner;

    /**
     * @return Banner
     */
    public function findTopBanner() : Banner;

    /**
     * @param int $id
     * @return Banner|null
     */
    public function findOneById(int $id): ?Banner;

    /**
     * @return Banner[]
     */
    public function findAll(): array;
}