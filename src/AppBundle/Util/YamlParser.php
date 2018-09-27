<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/16/2018
 * Time: 5:05 PM
 */

namespace AppBundle\Util;


use Symfony\Component\Yaml\Yaml;

class YamlParser
{
    private const FILE_PATH = "../app/config/parameters.yml";

    private $filePath;

    public function __construct(string $pathToFile = null)
    {
        if ($pathToFile != null)
            $this->filePath = $pathToFile;
        else
            $this->filePath = self::FILE_PATH;
    }

    public static function getMailerUsername(): string
    {
        return self::getFile()["parameters"]["mailer_user"];
    }

    public static function getFbAppId(): string
    {
        return self::getFile()["parameters"]["fb_app_id"];
    }

    public static function getFile(string $path = null): array
    {
        if ($path == null) $path = self::FILE_PATH;
        return Yaml::parse(file_get_contents($path));
    }

    public static function saveFile(array $params, string $path): void
    {
        $yamlFile = YAML::dump($params);
        file_put_contents($path, $yamlFile);
    }
}

