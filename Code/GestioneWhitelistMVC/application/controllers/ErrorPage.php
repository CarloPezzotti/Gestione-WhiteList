<?php
namespace Controllers;

use Libs\ViewLoader as ViewLoader;

/**
 * Classe ErrorPage che gestisce il controller della pagina di errore.
 */
class ErrorPage
{
    /**
     * Metodo che carica la pagina di errore 404
     */
    public static function error404()
    {
        ViewLoader::load('errors/404');
    }

    /**
     * Metodo che carica una pagina di errore personalizzata
     * 
     * @param $errorMessage messagio di errore che comparirà
     */
    public static function error($errorMessage){
        ViewLoader::load('errors/error',array("error"=>$errorMessage));
    }
}
