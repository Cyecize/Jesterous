<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/5/2018
 * Time: 8:14 PM
 */

namespace AppBundle\Contracts;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface IFileManager
{
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
}