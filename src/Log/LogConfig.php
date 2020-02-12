<?php
/**
 * Created by PhpStorm.
 * User: quand
 * Date: 4/13/2019
 * Time: 6:11 PM
 */

namespace Amarki\Log;


use Monolog\Logger;
use Symfony\Component\Yaml\Yaml;

class LogConfig
{
    //get the channel of Logger from config.yaml log: channel
    public static function getChannel()
    {
        $config = Yaml::parseFile(__DIR__.'/../../config.yaml');
        return (isset($config["log"]["channel"])) ? $config["log"]["channel"] : "Amarki";
    }

    //get the location to save log file from config.yaml log: file:
    public static function getFileLocation()
    {
        $config = Yaml::parseFile(__DIR__.'/../../config.yaml');
        return (isset($config["log"]["file"])) ? __DIR__."/".$config["log"]["file"] : __DIR__."/app.log";
    }

}