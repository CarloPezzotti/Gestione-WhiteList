<?php
class File
{
    private function __constructor()
    { }

    public static function readFile($file, $fileline = null)
    {
        if ($file != null) {     
            return file($file);
        }else{
            return file($file)[$fileline];
        }
    }

    public static function writeFile($file,$string, $fileline = null){
        $file = fopen($file,'a');
        fwrite($file,$string);
        fclose($file);
    }
}
