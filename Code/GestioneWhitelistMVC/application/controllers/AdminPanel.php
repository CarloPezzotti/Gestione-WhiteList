<?php

namespace Controllers;

use Libs\Application as Application;
use Libs\ViewLoader as ViewLoader;
use Libs\Auth as Auth;
use Models\Admin;
use Validate;

/**
 * Classe AdminPanel che gestisce il controller del pannello admin.
 */
class AdminPanel
{

    /**
     * Funzione che controlla che tipo di autenticazione è stato eseguito all'interno del sito.
     * Siccome ci troviamo nel pannello admin il seguente metodo ritorna true solo nel caso che l'accesso sia di tipo admin
     */
    private function checkAuth()
    {
        if (Auth::isAutenticated()) {
            if (Auth::getAutentication() == Auth::ADMIN) {
                return true;
            } else {
                Application::redirect('whitelistpanel/index');
            }
        } else {
            Application::redirect("home/login");
        }
    }

    /**
     * Metodo che mostra l'index del controller
     */
    public function index()
    {
        if ($this->checkAuth()) {
            ViewLoader::load('adminpanel/index', array("username" => Auth::getUsername()));
        }
    }

    /**
     * Metodo di logout, esegue logout pulendo e distruggendo la sessione.
     */
    public function logout()
    {
        Auth::logout();
        Application::redirect("home/login");
    }

    /**
     * Metodo di aggiunta di utenti nel database. Il seguente metodo riceve via post i dati degli utenti,
     * prima di inserirli vengono effetuati tutti i controlli del caso. Nel caso i controlli non dovessero 
     * passare viene mostrato a schermo un messaggio di errore.
     */
    public function insert()
    {
        if ($this->checkAuth()) {
            $error = false;

            //Username
            if (!empty($_POST["username"]) && !$error) {
                if (Validate::usernameValidate($_POST["username"]) != Validate::VALIDATE_FALIED) {
                    $username = $_POST["username"];
                }
            } else $error = true;

            //Name
            if (!empty($_POST["name"]) && !$error) {
                if (Validate::stringValidate($_POST["name"]) != Validate::VALIDATE_FALIED) {
                    $name = $_POST["name"];
                }
            } else $error = true;

            //Surname
            if (!empty($_POST["surname"]) && !$error) {
                if (Validate::stringValidate($_POST["surname"]) != Validate::VALIDATE_FALIED) {
                    $surname = $_POST["surname"];
                }
            } else $error = true;

            //Email
            if (!empty($_POST["email"]) && !$error) {
                if (Validate::emailValidate($_POST["email"]) != Validate::VALIDATE_FALIED) {
                    $email = $_POST["email"];
                }
            } else $error = true;

            //type
            if (!empty($_POST["type"]) && !$error) {
                $type = ($_POST["type"] == "Admin") ? 1 : 2;
            } else $error = true;

            if (!empty($_POST["password"]) && !$error) {
                $password = Validate::usernameValidate($_POST["password"]);
            } else $error = true;
            if (!$error) {
                $sql = "INSERT into user values(0,:username,:name,:surname,:email,:type,:password,1)";
                $values = array(
                    'username'    => $username,
                    'name'  => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'type' => $type,
                    'password' => hash('sha256', trim($password))
                );
                try {
                    Admin::doQuery($sql, $values);
                } catch (\PDOException $th) {
                    ViewLoader::load("adminpanel/index");
                }
            } else {
                ViewLoader::load("adminpanel/index", array('error' => $error));
            }
        }
    }

    /**
     * Metodo che va a modificare i dati degli utenti. Riceve via POST tutte le informazioni necessarie.
     * Se nella variabile POST non è settato il valore username, quest'utlimo viene utilizzato come get per andare 
     * ad aggiungere nei campi di input della pagine le informazione dell'utente.
     * 
     * @param $ID l'id dell'utente da modificare
     */
    public function modify($ID = null)
    {
        if ($this->checkAuth()) {
            if (!isset($_POST["username"])) {
                $info = Admin::getInfo($ID);
                ViewLoader::load("adminpanel/index", array(
                    'setID' => $info["id"],
                    'setUsername' => $info["username"],
                    'setName' => $info["name"],
                    'setSurname' => $info["surname"],
                    'setType' => $info["type"],
                    'setEmail' => $info["email"]
                ));
            } else {
                $values = array(
                    'username'    => htmlspecialchars($_POST["username"]),
                    'name'  => htmlspecialchars($_POST["name"]),
                    'surname' => htmlspecialchars($_POST["surname"]),
                    'email' => htmlspecialchars($_POST["email"]),
                    'type' => (htmlspecialchars($_POST["type"]) == "Admin") ? 1 : 2
                );
                if (!empty($_POST["password"])) {
                    $password = Validate::usernameValidate($_POST["password"]);
                    $values["password"] = hash('sha256', trim($password));
                    $values["setPassword"] = 1;
                    var_dump($values);
                }

                Admin::modify($ID, $values);
                ViewLoader::load("adminpanel/index");
            }
        }
    }

    /**
     * Metodo che rimuove dal database un utente.
     * 
     * @param $ID l'id dell'utente da rimuovere
     */
    public function delete($ID)
    {
        if ($this->checkAuth()) {
            Admin::delete($ID);
            ViewLoader::load('adminpanel/index', $_SESSION);
        }
    }
}
