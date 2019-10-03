<?php
namespace Models;

use Libs\Database as Database;

class Users
{

    const USERNAME_NOT_EXIST = 0;
    const PASSWORD_WRONG = 1;
    const LOGIN_SUCCESS = 2;

    public static function get()
    {
        $query = Database::get()->prepare("select * from user");
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function exist($username,$password)
    {
        $query = Database::get()->prepare("select password from user where username=:username");
        $query->bindParam(":username",$username);
        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        if(count($result)==0){
            return self::USERNAME_NOT_EXIST;
        }else if(password_verify($password,$result[0]["password"])){
            return self::LOGIN_SUCCESS;
        }else{
            return self::PASSWORD_WRONG;
        }
    }

    public static function getInfo($username){
        $query = Database::get()->prepare("select * from user where username=:username");
        $query->bindParam(":username",$username);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    public static function change($username,$newpassword){
        try{
            $conn = Database::get();          
            $stmt = $conn->prepare("UPDATE user set password=:password where username=:username");
            $stmt->bindParam(':username', $username);
            $stmt->bindValue(':password',  password_hash($newpassword, PASSWORD_BCRYPT));
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
