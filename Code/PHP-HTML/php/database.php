<?php
    class Database
    {
        private static $host = "10.20.143.220";
        private static $username = "root";
        private static $password = "Password&1";
        private static $database = "gestioneWhitelist";
        private static $port = 3307;
        private static $connection;

        private function __constructor(){}

        public static function getConnection(){
            if(!empty(self::$connection)){
                return self::$connection;
            }else{           
                $dsn = "mysql:port=".self::$port.";host=".self::$host.";dbname=".self::$database;
                try {
                    self::$connection = new PDO($dsn, self::$username, self::$password);
                    return self::$connection;
                } catch (PDOException $e) {
                    throw $e;
                }
            }
        }
    }
?>