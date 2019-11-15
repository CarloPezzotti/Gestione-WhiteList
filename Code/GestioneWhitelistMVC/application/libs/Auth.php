<?php
/**
 * Applicazione principale.
 * Base MVC di @filippofinke modificata da @carlopezzotti.
 */
namespace Libs;


class Auth
{

    const ADMIN = 10;
    const USER = 20;

    /**
     * Metodo generico che verifica se si è autenticati oppure no.
     */
    public static function isAutenticated()
    {
        return isset($_SESSION["auth"]);
    }

    /**
     * Metodo gette che ritorna il tipo di autenticazione.
     * 
     * @return ritorna il valore contenuto nella variabile $_SESSION["auth"], nel caso quest'ultimo fosse nulla false.
     */
    public static function getAutentication()
    {
        if (isset($_SESSION["auth"])) {
            return $_SESSION["auth"];
        }
        return false;
    }

    /**
     * Metodo che esegue l'autenticazione.
     * 
     * @param $username l'username dell'utente che esegue l'autenticazione.
     * @param $type il tipo dell'utente (USER o ADMIN).
     */
    public static function auth($username, $type)
    {
        $_SESSION["username"] = $username;
        $_SESSION["auth"] = $type;
    }

    /**
     * Metodo che ritorna l'username dell'utente
     * @return l'username dell'utente
     */
    public static function getUsername()
    {
        if (isset($_SESSION["username"])) {
            return $_SESSION["username"];
        }
        return false;
    }

    /**
     * Metodo di logout, esegue logout pulendo e distruggendo la sessione.
     */
    public static function logout()
    {
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        session_destroy();
    }
}
