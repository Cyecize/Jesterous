<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/5/2018
 * Time: 10:23 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ImageBindingModel
{
    /**
     * @Assert\NotNull(message="Select image")
     * @Assert\File(
     *     maxSize="2M", maxSizeMessage="File size more than 2M",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage="Please upload a valid jpg"
     * )
     */
    private $file;

    public function __construct()
    {

    }

    public static function imageOverload(UploadedFile $file){
        $bm = new ImageBindingModel();
        $bm->file = $file;
        return $bm;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }
}