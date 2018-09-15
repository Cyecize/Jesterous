<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/5/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\IFileManager;
use AppBundle\Entity\User;
use AppBundle\Exception\IllegalArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager implements IFileManager
{

    public function __construct()
    {

    }

    public function removeDirectory(string $dirPath): void
    {
        if (strpos($dirPath, '../') != false)
            throw new IllegalArgumentException("Hacker!");

        if (!is_dir($dirPath)) {
            if (file_exists($dirPath) !== false) {
                unlink($dirPath);
            }
            return;
        }

        if ($dirPath[strlen($dirPath) - 1] != '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->removeDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
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

    /**
     * @param UploadedFile $file
     * @param User $user
     * @return string
     */
    public function uploadFileToUser(UploadedFile $file, User $user): string
    {
        $pathToUser = sprintf(Config::USER_FILES_PATH_FORMAT, $user->getUsername());
        $imageName = $this->uploadFile($file, $pathToUser);
        return "/" . $pathToUser . $imageName;
    }
}