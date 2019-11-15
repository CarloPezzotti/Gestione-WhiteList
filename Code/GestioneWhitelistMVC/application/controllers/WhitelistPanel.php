<?php

namespace Controllers;

use Libs\Application as Application;
use Libs\ViewLoader as ViewLoader;
use Libs\Auth as Auth;
use Models\Whitelist;

/**
 * Classe WhitelistPanel che gestisce il controller della pagina della whitelist.
 */
class WhitelistPanel
{

    /**
     * Funzione che controlla che tipo di autenticazione è stato eseguito all'interno del sito.
     * Siccome ci troviamo nel pagina della whitelist il seguente metodo ritorna 
     * true nel caso si è USER oppure ADMIN.
     */
    private function checkAuth()
    {
        if (Auth::isAutenticated()) {
            if (Auth::getAutentication() == Auth::USER || Auth::getAutentication() == Auth::ADMIN) {
                return true;
            } else {
                ViewLoader::load('home/login');
            }
        } else {
            Application::redirect("home/login");
        }
    }

    /**
     * Metodo che mostra l'index del controller. Dopo aver effetuato dei controlli sul file.
     */
    public function index()
    {
        if ($this->checkAuth()) {
            try {
                Whitelist::get();
                ViewLoader::load('whitelist/index');
            } catch (\Exception $th) {
                Application::redirect('errorpage/error/'.\htmlspecialchars($th->getMessage()));
            }
        }
    }

    /**
     * Metodo chiamato per inserire all'interno del file specifico un sito.
     * La pagina può variare il contenuto in base ai messaggi che vengono generati.
     */
    public function insert()
    {
        if (Auth::getAutentication() == Auth::USER || Auth::getAutentication() == Auth::ADMIN) {
            if (isset($_POST["fstPart"]) && isset($_POST["middlePart"]) && isset($_POST["lastPart"])) {
                $fstPart = \htmlspecialchars($_POST["fstPart"]);
                $middlePart = \htmlspecialchars($_POST["middlePart"]);
                $lastPart = \htmlspecialchars($_POST["lastPart"]);
                $lastPart = ($lastPart == "*") ? "" : $lastPart;
                $result = Whitelist::add("\r\n$lastPart.$middlePart.$fstPart");
                if ($result == Whitelist::SITE_ALREADY_EXIST) {
                    ViewLoader::load('whitelist/index', array(
                        "info" => "Site already exist"
                    ));
                } else if ($result == Whitelist::ADDING_SITE_ERROR) {
                    ViewLoader::load('whitelist/index', array(
                        "error" => "Fatal error. Try later"
                    ));
                } else if ($result == Whitelist::DOMAIN_FORMAT_INVALID) {
                    ViewLoader::load('whitelist/index', array(
                        "error" => "Domain format invalid :("
                    ));
                }
                else if ($result == Whitelist::SITE_ADDED) {
                    ViewLoader::load('whitelist/index');
                    Whitelist::reloadProxy();
                }
                
            }
        }
    }

    /**
     * Metodo che rimuove un sito dal file.
     * 
     * @param $site il sito da voler rimuovere.
     */
    public function remove($site)
    {
        if ($this->checkAuth()) {
            try {
                Whitelist::remove(trim(base64_decode($site)));
                Whitelist::reloadProxy();
                ViewLoader::load('whitelist/index');
            } catch (\Exception $th) {
                Application::redirect('errorpage/error/'.\htmlspecialchars($th->getMessage()));
            }
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
}
