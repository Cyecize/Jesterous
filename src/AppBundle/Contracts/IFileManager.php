<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/5/2018
 * Time: 8:14 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface IFileManager
{
    /**
     * @param string $dir
     * @throws IllegalArgumentException
     */
    public function removeDirectory(string $dir) : void ;

    /**
     * @param string $filePath
     */
    public function removeFile(string $filePath): void;

    /**
     * @param $file
     * @param string $path
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $path): string;

    /**
     * @param UploadedFile $file
     * @param User $user
     * @return string
     */
    public function uploadFileToUser(UploadedFile $file, User $user): string;

}