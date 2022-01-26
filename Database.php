<?php
class MySQLDB {

    private static $dbhost = "localhost";
    private static $dbuser = "root";
    private static $dbpass = "";
    private static $db = "test";
    public $conn = null;

    function __construct() {
        $this->conn = self::openCon();
    }

    public static function getInstance() {
        return new MySQLDB();
    }

    public static function openCon()
    {
        $conn = new mysqli(self::$dbhost, self::$dbuser, self::$dbpass,self::$db) or die("Connect failed: %s\n". $conn -> error);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
 
    public static function closeCon($conn)
    {
        $conn -> close();
    }

    public function runQuery($sql) {

        try {
            $result = $this->conn->query($sql);
            $this->closeCon($this->conn);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        return $result;
    }

    public function getCountNum($sql) {
        $result = $this->runQuery($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        else {
            return ['num' => 0];
        }
        
    }

    public function getArrayResult($sql) {
        $result = $this->runQuery($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $json_data[] = $row;
            }
            return $json_data;
        }
        else {
            return [];
        }
        
    }
    
}