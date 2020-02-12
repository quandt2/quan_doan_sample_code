<?php
/**
 * Created by PhpStorm.
 * User: quand
 * Date: 4/13/2019
 * Time: 11:58 AM
 */

namespace Amarki\Model;
use Amarki\Log\LogConfig;
Use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Property
{

    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DbConnection();
        $this->dbConnection->openConnection();
    }

    public function getList($params = array()) {
        $logger = new Logger(LogConfig::getChannel());

        $logger->pushHandler(new StreamHandler(LogConfig::getFileLocation(), Logger::DEBUG));
        if (count($params) == 0) throw new \Exception("No params");
        try {

            $columns = $totalRecords = $data = array();

            //define index of column
            $columns = array(
                0 =>'property_id',
                1 => 'address',
                2 => 'property_value',
                3 => 'property_listing_date',
                4 =>'property_exp_date',
                5 => 'property_long_desc',
            );

            $where = $sqlTot = $sqlRec = "";

            // check search value exist
            if( !empty($params['search']['value']) ) {
                $logger->addInfo("filter data table with key:". $params['search']['value']);
                $where .=" WHERE ";
                $where .=" ( property_id LIKE '".$params['search']['value']."%' ";
                $where .=" OR CONCAT(property_address, ', ', property_city, ', ',property_state, ' ',property_zip) LIKE '".$params['search']['value']."%' ";
                $where .=" OR property_value LIKE '".$params['search']['value']."%' )";
            }

            // getting total number records without any search
            $sql = "SELECT property_id, CONCAT(property_address, ', ', property_city, ', ',property_state, ' ',property_zip) as address, property_value, DATE_FORMAT(property_listing_date, \"%b %d, %Y\") as property_listing_date,
  DATE_FORMAT(property_exp_date, \"%b %d, %Y\") as property_exp_date, property_long_desc  FROM `property` ";
            $sqlTot .= "SELECT COUNT(*) FROM property";
            $sqlRec .= $sql;
            //concatenate search sql if value exist
            if(isset($where) && $where != '') {
                $sqlRec .= $where;
                $sqlTot .= $where;
            }


            $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
            $logger->addInfo("sql query: $sqlRec");
            $this->dbConnection->query($sqlRec);
            $data = $this->dbConnection->getRecord();
            $totalRecords = $this->dbConnection->getTotal($sqlTot);

            foreach ($data as $row) {
                $logger->addInfo("get property record: ", $row);
            }
            $json_data = array(
                "draw"            => intval( $params['draw'] ),
                "recordsTotal"    => intval( $totalRecords ),
                "recordsFiltered" => intval($totalRecords),
                "data"            => $data   // total data array
            );
            return json_encode($json_data);
        } catch (\Exception $e) {
            $logger->addError($e->getMessage());
        }


    }

}