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

        public static function executeQuery($sql, $values, $types = false) {
            $stmt = Database::getConnection()->prepare($sql);
            foreach($values as $key => $value) {
                if($types) {
                    $stmt->bindValue(":$key",$value,$types[$key]);
                } else {                 
                    if(is_int($value))        { $param = PDO::PARAM_INT; }
                    else if(is_bool($value))   { $param = PDO::PARAM_BOOL; }
                    else if(is_null($value))   { $param = PDO::PARAM_NULL; }
                    else if(is_string($value)) { $param = PDO::PARAM_STR; }
                    else { $param = FALSE;}
                    if($param) $stmt->bindValue(":$key",$value,$param);
                }
            }
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function removeFromTable($table,$param,$value){
            $stmt = Database::getConnection()->prepare("Delete from $table where $param = '$value'");
            $stmt->execute();
        }
    }
?>