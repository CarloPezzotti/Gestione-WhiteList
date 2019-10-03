<?php

namespace Controllers;

use Libs\Application as Application;
use Libs\ViewLoader as ViewLoader;
use Libs\Auth as Auth;
use Models\Admin;
use Models\Users as Users;

class AdminPanel
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        ViewLoader::load('adminpanel/index', $_SESSION);
    }

    public function logout()
    {
        Auth::logout();
        Application::redirect("home/index");
    }

    public function insert()
    {
        $error = "";
        if (!empty($_POST["username"]))
            $username = htmlspecialchars($_POST["username"]);
        else
            $error .= "Username required!<br>";
        if (!empty($_POST["name"]))
            $name = htmlspecialchars($_POST["name"]);
        else
            $error .= "Name required!<br>";
        if (!empty($_POST["surname"]))
            $surname = htmlspecialchars($_POST["surname"]);
        else
            $error .= "Surname required!<br>";
        if (!empty($_POST["email"]))
            $email = htmlspecialchars($_POST["email"]);
        else
            $error .= "Email required!<br>";
        if (!empty($_POST["type"]))
            $type = htmlspecialchars($_POST["type"]);
        else
            $error .= "Type required!<br>";
        if (!empty($_POST["password"]))
            $password = htmlspecialchars($_POST["password"]);
        else
            $error .= "Password required!<br>";

        if (isset($username)) {
            $sql = "INSERT into user values(0,:username,:name,:surname,:email,:type,:password,1)";
            $values = array(
                'username'    => $username,
                'name'  => $name,
                'surname' => $surname,
                'email' => $email,
                'type' => ($type == "Admin") ? 1 : 2,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            );
            try {
                Admin::doQuery($sql, $values);
            } catch (\PDOException $th) {
                ViewLoader::load("adminpanel/index");
            }
        } else {
            ViewLoader::load("adminpanel/index",array('error'=>$error));
        }
    }

    public function modify($ID){
        if(!isset($_POST["username"])){
            $info = Admin::getInfo($ID);
            ViewLoader::load("adminpanel/index",array(
                'setID'=>$info["id"],
                'setUsername'=>$info["username"],
                'setName'=>$info["name"],
                'setSurname'=>$info["surname"],
                'setType'=>$info["type"],
                'setEmail'=>$info["email"]
            ));
        }else{
            $values = array(
                'username'    => htmlspecialchars($_POST["username"]),
                'name'  => htmlspecialchars($_POST["name"]),
                'surname' => htmlspecialchars($_POST["surname"]),
                'email' => htmlspecialchars($_POST["email"]),
                'type' => (htmlspecialchars($_POST["type"]) == "Admin") ? 1 : 2
            );
            $password = htmlspecialchars($_POST["password"]);
            if (!empty($password))
                $values["password"]=password_hash($password, PASSWORD_BCRYPT);

            Admin::modify($ID,$values);
            ViewLoader::load("adminpanel/index");
        }
    }

    public function delete($ID)
    {
        Admin::delete($ID);
        ViewLoader::load('adminpanel/index', $_SESSION);
    }
}
