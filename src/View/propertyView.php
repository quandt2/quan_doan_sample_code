<?php
/**
 * Created by PhpStorm.
 * User: quand
 * Date: 4/13/2019
 * Time: 11:42 AM
 */
require_once '../../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Amarki\Log\LogConfig;
error_reporting(E_ALL);
ini_set('display_errors', '1');

$logger = new Logger(LogConfig::getChannel());

$logger->pushHandler(new StreamHandler(LogConfig::getFileLocation(), Logger::DEBUG));
try {
    $params = $_REQUEST;
    $property = new \Amarki\Model\Property();
    $logger->addInfo("Data table change");
    $list = $property->getList($params);
    echo $list;
} catch (Exception $e) {
    $logger->addError($e->getMessage());
}