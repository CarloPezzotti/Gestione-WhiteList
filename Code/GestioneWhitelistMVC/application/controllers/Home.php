<?php

namespace Controllers;

use Libs\Application as Application;
use Libs\ViewLoader as ViewLoader;
use Libs\Auth as Auth;
use Models\Users as Users;

class Home
{
    public function index()
    {
        if (!Auth::isAuthenticated()) {
            ViewLoader::load('home/index');
        } else {
            Application::redirect("home/login");
        }
    }

    public function login()
    {
        if (empty($_POST["username"]) && empty($_POST["password"])) {
            ViewLoader::load("home/login");
        } else {
            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);
            if (is_string($username) && is_string($password)) {
                $login = Users::exist($username, $password);
                if ($login == Users::LOGIN_SUCCESS){
                    $info = Users::getInfo($username);
                    if($info["setpassword"]==1){
                        ViewLoader::load("home/login",array(
                            "setpassword" =>1,
                            "surname"=>$info["surname"],
                            "username"=>$info["username"]
                        ));
                    }else{
                        Auth::auth();
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        foreach ($info as $key => $value) {
                            $_SESSION[$key] = $value;
                        }
                        if($info["type"]==1)
                            Application::redirect("adminpanel/index");
                        else if($info["type"]==2)
                            Application::redirect("proxypanel/index");

                    }
                }else if($login == Users::USERNAME_NOT_EXIST){
                    ViewLoader::load('home/login', array(
                        'error' => "Username not found!"
                    ));
                }else{
                    ViewLoader::load('home/login', array(
                        'error' => "Password wrong!"
                    ));
                }
            }
        }
    }

    public function changePassword(){
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);
        Users::change($username,$password);
        ViewLoader::load('home/login', array(
            'success' => "Password changed, login again"
        ));
    }

    public function logout()
    {
        Auth::logout();
        Application::redirect("home/index");
    }
}
