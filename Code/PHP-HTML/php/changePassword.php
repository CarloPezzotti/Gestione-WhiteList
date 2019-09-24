<?php
    require_once "database.php";
    class ChangePassword
    {
        private function __constructor(){}

        public static function change($username,$newpassword){
            try{
                $conn = Database::getConnection();          
                $stmt = $conn->prepare("UPDATE user set password=:password where username=:username");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $newpassword);
                $stmt->execute(); 
                $stmt = $conn->prepare("UPDATE user set setpassword='0' where username=:username");
                $stmt->bindParam(':username', $username);
                $stmt->execute(); 
                return true;
            } catch (\Throwable $th) {
                return false;
            }
        }
    }
    
?>