<?php
include_once 'Database.php';
class API {

    public static function getInstance() {
        return new API();
    }

    /**
     * Report the number of crimes from a given area. (Ex, if I want the number of crimes in the SouthWest area, it should return the number.
     * @param $area - area code
     * @return numbers of crimes filtered by area
     */
    public function getCrimesNumberByAreaCode($area) {
        $mysql = MySQLDB::getInstance();
        
        $sql = sprintf(
            "SELECT COUNT(DR_NO) AS num
            FROM crime_data_from_2020_to_present_csv
            WHERE AREA = '%s'", 
            $mysql->conn->real_escape_string($area));
        
        $num = $mysql->getCountNum($sql);
        unset($mysql);

        return $num['num'];
    }

    /**
     * Report the number of crimes from a given area. (Ex, if I want the number of crimes in the SouthWest area, it should return the number.
     * @param $area - area name
     * @return numbers of crimes filtered by area name
     */
    public function getCrimesNumberByAreaName($area) {
        $mysql = MySQLDB::getInstance();

        $sql = sprintf(
            "SELECT COUNT(DR_NO) AS num
            FROM crime_data_from_2020_to_present_csv
            WHERE `AREA NAME` = '%s'", 
            $mysql->conn->real_escape_string($area));
        
        $num = $mysql->getCountNum($sql);
        unset($mysql);

        return $num['num'];
    }

    /**
     *  Report the number of crimes for a given crime.
     * @param $type - crime type
     * @return numbers of crimes filtered by crime type
     */
    public function getCrimesNumberByCrimeType($type) {
        $mysql = MySQLDB::getInstance();
        
        $sql = sprintf(
            "SELECT COUNT(DR_NO) AS num
            FROM crime_data_from_2020_to_present_csv
            WHERE `CRM CD DESC` = '%s'", 
            $mysql->conn->real_escape_string($type));
        
        $num = $mysql->getCountNum($sql);
        unset($mysql);

        return $num['num'];
    }

    /**
     * Show the Addresses (Street City Zip) for a given crime type. (Ex, if you are looking for Battery – Simple Assault, it should return all the addresses).
     * @param $type - crime type
     * @return addresses of crimes filtered by crime type
     */
    public function getAddressByCrimeType($type) {
        $mysql = MySQLDB::getInstance();

        $sql = sprintf(
            "SELECT `LOCATION`
            FROM crime_data_from_2020_to_present_csv
            WHERE `CRM CD DESC` = '%s'", 
            $mysql->conn->real_escape_string($type));
        
        $result = $mysql->getArrayResult($sql);
        foreach($result as $row) {
            $res[] = $row['LOCATION'];
        }
        unset($mysql);

        return array_unique($res);
    }

    /**
     * Please use pagination methods. If the results are in the thousands, please return the first hundred with options to get the next page.
     * @param $type - crime type, $page -  page number
     * @return addresses of crimes filtered by crime type and page number
     */
    public function getAddressPageByCrimeType($type, $page=1) {
        $mysql = MySQLDB::getInstance();

        $num_sql = sprintf(
            "SELECT COUNT(DR_NO) AS num
            FROM crime_data_from_2020_to_present_csv
            WHERE `CRM CD DESC` = '%s'", 
            $mysql->conn->real_escape_string($type));
        $num = $mysql->getCountNum($num_sql);
        unset($mysql);
        
        $mysql = MySQLDB::getInstance();
        $sql = sprintf(
            "SELECT `LOCATION`
            FROM crime_data_from_2020_to_present_csv
            WHERE `CRM CD DESC` = '%s'
            LIMIT %s, %s", 
            $mysql->conn->real_escape_string($type), 100*($page-1), 100);
        
        $result = $mysql->getArrayResult($sql);
        
        $pages = ceil($num['num']/100);
        foreach($result as $row) {
            $res[] = $row['LOCATION'];
        }
        unset($mysql);

        return array(
            'page_num' => $page,
            'pages' => $pages,
            'records' => $num['num'],
            'content' => array_unique($res)
        );
    }

}