<?php
/**
 * Classe Validate si occupa di fornire delle metodi per la validazione.
 */
class Validate
{

    const VALIDATE_FALIED = 10;

    /**
     * Metodo che verifica se il valore sia una stringa senza caretteri speciali.
     * @param $value il valore da controllare
     * @return $value oppure un messagio di errore
     */
    public static function stringValidate($value)
    {
        $value = self::test_input($value);
        if (preg_match('/^[A-Za-z0-9_-]*$/', $value)) {
            $value = htmlspecialchars($value);
            return $value;
        }
        return self::VALIDATE_FALIED;
    }

    /**
     * Metodo che verifica se il valore sia un intero.
     * @param $value il valore da controllare
     * @return $value oppure un messagio di errore
     */
    public static function integerValidate($value)
    {
        $value = self::test_input($value);
        $value = stringValidate($value);
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            return $value;
        }
        return self::VALIDATE_FALIED;
    }

    /**
     * Metodo che verifica se il valore sia una mail.
     * @param $value il valore da controllare
     * @return $value oppure un messagio di errore
     */
    public static function emailValidate($value)
    {
        $value = self::test_input($value);
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $value;
        }
        return self::VALIDATE_FALIED;
    }

    /**
     * Metodo che verifica se il valore sia un username.
     * @param $value il valore da controllare
     * @return $value oppure un messagio di errore
     */
    public static function usernameValidate($value){
        $value = self::test_input($value);
        return $value;
    }

    /**
     * Metodo che rimuove caratteri speciali.
     * @param $data il valore da controllare
     * @return $data oppure un messagio di errore
     */
    private static function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
