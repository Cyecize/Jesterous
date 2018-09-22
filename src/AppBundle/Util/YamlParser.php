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
    private static $FILE_PATH = "../app/config/parameters.yml";

    public function __construct()
    {

    }

    public function getYamlParameters() : array
    {
        return self::getFile()["parameters"];
    }

    public function saveYamlParameters(array $params) : void{
        $file = self::getFile()["parameters"];
        $resFile = self::getFile();
        foreach($file as $key => $val){
            if(array_key_exists($key, $params)){
                $resFile["parameters"][$key] = $params[$key];
            }
        }
        $yamlFile = YAML::dump($resFile);
        file_put_contents(self::$FILE_PATH, $yamlFile);
    }

    public static function getMailerUsername() : string {
        return self::getFile()["parameters"]["mailer_user"];
    }

    public static function getFbAppId() : string {
        return self::getFile()["parameters"]["fb_app_id"];
    }

    private static function getFile() : array {
        return Yaml::parse(file_get_contents(self::$FILE_PATH));
    }

}