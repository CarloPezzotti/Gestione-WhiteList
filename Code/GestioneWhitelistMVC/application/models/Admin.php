<?php

namespace Models;

use Libs\Database as Database;

class Admin
{


    public static function get()
    {
        return Users::get();
    }

    public static function exist($username, $password)
    {
        return Users::exist($username, $password);
    }

    public static function updateInfo($username, $info)
    {
        $query = Database::get()->prepare("select * from user where username=:username");
        $query->bindParam(":username", $username);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    public static function getTable()
    {
        $stmt = Database::get()->prepare("SELECT 
                    id as ID,
                    username as Username,
                    name as Name,
                    surname as Surname,
                    email as Email,
                    type as Type,
                    password as Password
                    FROM user
                ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $conn = Database::get();
        $stmt = $conn->prepare("delete from user where id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public static function modify($id, $values)
    {

        $sql = "UPDATE user set 
                username=:username,
                name=:name,
                surname=:surname,
                email=:email,
                type=:type
                " . (empty($values["password"]) ? '' : ' ,password=:password ') . "
                WHERE id=$id";
        try {
            Admin::doQuery($sql, $values);
        } catch (\PDOException $th) { }
    }

    public static function getInfo($id)
    {
        $query = Database::get()->prepare("select * from user where id=:id");
        $query->bindParam(":id", $id);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    public static function doQuery($sql, $values, $types = false)
    {
        $stmt = Database::get()->prepare($sql);
        foreach ($values as $key => $value) {
            if ($types) {
                $stmt->bindValue(":$key", $value, $types[$key]);
            } else {
                if (is_int($value)) {
                    $param = \PDO::PARAM_INT;
                } else if (is_bool($value)) {
                    $param = \PDO::PARAM_BOOL;
                } else if (is_null($value)) {
                    $param = \PDO::PARAM_NULL;
                } else if (is_string($value)) {
                    $param = \PDO::PARAM_STR;
                } else {
                    $param = FALSE;
                }
                if ($param) $stmt->bindValue(":$key", $value, $param);
            }
        }
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
