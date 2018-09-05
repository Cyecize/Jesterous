<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/5/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\IFileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager implements IFileManager
{

    public function __construct()
    {

    }

    /**
     * @param string $filePath
     */
    public function removeFile(string $filePath): void
    {
        if (file_exists($filePath))
            unlink($filePath);
    }

    /**
     * @param $file
     * @param string $path
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $path): string
    {
        $imgName = md5($file->getFilename()) . "." . $file->guessExtension();
        $file->move($path, $imgName);
        return $imgName;
    }
}