<?php
class Database {
    
    public static $dbName;
    private static $db;
    
    public static function getDB($dbName = 'cs4773', $configFile = 'myConfig.ini') {
        Database::$dbName = $dbName;
        
        if (!isset(Database::$db)) {
            try {
                // read database config info from file
                $configArray = parse_ini_file(dirname(__FILE__).DIRECTORY_SEPARATOR.
                        '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$configFile);
                if ($configArray === false)
                    throw new RuntimeException("Database configuration file not found");
                $userName = $configArray["username"];
                $pass = $configArray["password"];
                
                // open the connection
                Database::$db = new PDO("mysql:host=localhost;dbname=$dbName;charset=utf8", $userName, $pass);
                Database::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e) {
                echo "Failed to connect to database $dbName: " . $e->getMessage();
            }
        }
        
        return Database::$db;
    }
}
?>