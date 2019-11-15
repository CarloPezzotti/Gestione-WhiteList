<?php

namespace Controllers;

use Libs\Application as Application;
use Libs\ViewLoader as ViewLoader;
use Libs\Auth as Auth;
use Models\Users as Users;
use Validate;

/**
 * Classe Home che gestisce il controller della pagina di home (login).
 */
class Home
{
    /**
     * Funzione che mostra l'index della pagina ovvero il login
     */
    public function index()
    {
        Application::redirect("home/login");    
    }

    /**
     * Metodo che cerca di eseguire il login al database, vengono salvati in sessione
     * l'username dell'utente e il tipo di permessi (ADMIN o USER). Dopodichè si viene 
     * rendirizzati su una nuova pagina.
     */
    public function login()
    {
        if (empty($_POST["username"]) && empty($_POST["password"])) {
            ViewLoader::load("home/login",array(
                "error"=>true
            ));
        } else {
            $username = Validate::usernameValidate($_POST["username"]);
            $password = Validate::usernameValidate($_POST["password"]);
            if (is_string($username)) {
                $login = Users::exist($username, $password);
                if ($login == Users::LOGIN_SUCCESS){
                    $info = Users::getInfo($username);
                    if($info["setpassword"]==1){
                        ViewLoader::load("home/login",array(
                            "setpassword" =>1,
                            "surname"=>$info["surname"]. " ".$info["name"],
                            "username"=>$info["username"]
                        ));
                    }else{
                        $type = ($info["type"]==1)?Auth::ADMIN:Auth::USER;
                        Auth::auth($info["username"],$type);
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        foreach ($info as $key => $value) {
                            $_SESSION[$key] = $value;
                        }
                        if($type == Auth::ADMIN)
                            Application::redirect("adminpanel/index");
                        else if($type==Auth::USER)
                            Application::redirect("whitelistpanel/index");

                    }
                }else if($login == Users::USERNAME_NOT_EXIST){
                    ViewLoader::load('home/login', array(
                        'error' => "Username not found!"
                    ));
                }else if($login == Users::PASSWORD_WRONG){
                    ViewLoader::load('home/login', array(
                        'error' => "Password wrong!"
                    ));
                }else{
                    ViewLoader::load('home/login', array(
                        'error' => "Error :("
                    ));
                }
            }else{
                ViewLoader::load('home/login', array(
                    'error' => "Error :("
                ));
            }
        }
    }

    /**
     * Metodo utilizzato per il cambio della password.
     */
    public function changePassword(){
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);
        Users::change($username,$password);
        ViewLoader::load('home/login', array(
            'success' => "Password changed, login again"
        ));
    }

    /**
     * Metodo di logout, esegue logout pulendo e distruggendo la sessione.
     */
    public function logout()
    {
        Auth::logout();
        Application::redirect("home/index");
    }
}
