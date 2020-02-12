<?php
/**
 * Created by PhpStorm.
 * User: quand
 * Date: 4/13/2019
 * Time: 2:49 AM
 */

namespace Amarki\Model;
use Symfony\Component\Yaml\Yaml;
use PDO;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
Use Amarki\Log\LogConfig;
error_reporting(E_ALL);
ini_set('display_errors', '1');
class DbConnection
{
    private $connect;
    private $user;
    private $pass;
    private $options = array();
    private $record = array();
    private $rows = null;
    private $con = null;
    private $logger;

    public function __construct()
    {
        $this->getDbConnectionInfo();

    }

    //Get the db connection info from config.yaml
    public function getDbConnectionInfo()
    {
        try {
            $this->logger = new Logger(LogConfig::getChannel());
            $this->logger->pushHandler(new StreamHandler(LogConfig::getFileLocation(), Logger::DEBUG));
            $this->logger->addInfo("Getting DB configuration");
            $config = Yaml::parseFile(__DIR__.'/../../config.yaml');
            $configValue = isset($config["mysql"]) ? $config["mysql"] : array();
            if (count($configValue) < 1) throw new \Exception("No Db configuration, check config file again");
            $this->connect = (isset($configValue["connect"])) ? $configValue["connect"] : "";
            $this->user = (isset($configValue["user"])) ? $configValue["user"] : "";
            $this->pass = (isset($configValue["pass"])) ? $configValue["pass"] : "";
            $this->options = (isset($configValue["options"])) ? $configValue["options"] : "";
        } catch (\Exception $e) {
            $this->logger->addError("There is some problem in getting db config: " . $e->getMessage());
        }

    }

    public function openConnection()
    {
        try {
            $this->logger->addInfo("Opening new DB connection");
            $this->con = new PDO($this->connect, $this->user, $this->pass, $this->options);
            return $this->con;
        } catch (\PDOException $e) {
            $this->logger->addError("There is some problem in connection: " . $e->getMessage());
        }
    }

    public function query($sql, $method = false)
    {
        try {
            if (!$this->con) {
                throw new \PDOException("No DB Connection");
            }
            $smt = $this->con->prepare($sql);
            $ex = $smt->execute();
            if (!$ex) {
                throw new \PDOException("Wrong sql");
            }
            $this->rows = $smt->rowCount();
            if ($method != "insert") {
                $this->record = $smt->fetchAll();
            }
        } catch (\PDOException $e) {
            $this->logger->addInfo("There is some problem while executing query: " . $e->getMessage());
        }
    }

    //get count of total record
    public function getTotal($query)
    {
        try {
            $con = new PDO($this->connect, $this->user, $this->pass, array("PDO::FETCH_ASSOC"));
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            $this->logger->addError( "There is some problem while query: " . $e->getMessage());
        }
    }

    public function getRecord()
    {
        return $this->record;
    }


}