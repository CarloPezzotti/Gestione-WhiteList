<?php

namespace Models;

use Exception;

class Whitelist
{

    const SITE_ADDED = 10;
    const SITE_ALREADY_EXIST = 20;
    const ADDING_SITE_ERROR = 30;
    const DOMAIN_FORMAT_INVALID = 40;
    const FILE_NOT_EXIST = 1;
    const PERMISSION_DENIED = 2;


    public static function getCategories()
    {
        if (file_exists(SQUID_WHITELIST)) {
            if (is_readable(SQUID_WHITELIST) || \is_writable(SQUID_WHITELIST)) {
                $all = file(SQUID_WHITELIST)[0];
                $all = \substr($all, 1, strlen($all));
                return explode(";", $all);
            } else {
                throw new Exception("Permission denied", self::PERMISSION_DENIED);
            }
        } else {
            throw new Exception("File not exist", self::FILE_NOT_EXIST);
        }
    }

    public static function get()
    {
        if (file_exists(SQUID_WHITELIST)) {
            if (is_readable(SQUID_WHITELIST) || \is_writable(SQUID_WHITELIST)) {

                $file = file(SQUID_WHITELIST);
                $categories = Whitelist::getCategories();
                $obj =  (object) array();
                $current = "";
                for ($i = 1; $i < \count($file); $i++) {
                    $line = trim(\substr($file[$i], 1, strlen($file[$i])));
                    for ($j = 0; $j < \count($categories); $j++) {
                        if ($line == trim($categories[$j])) {
                            $current = trim($categories[$j]);
                            $obj->$current = array();
                        }
                    }
                    if ($file[$i]{
                        0} != "#") {
                        array_push($obj->$current, trim($file[$i]));
                    }
                }
                return $obj;
            } else {
                throw new Exception("Permission denied", self::PERMISSION_DENIED);
            }
        } else {
            throw new Exception("File not exist", self::FILE_NOT_EXIST);
        }
    }

    public static function add($site, $categorie)
    {
        $sites = Whitelist::get();
        foreach ($sites as $key => $value) {
            foreach ($value as $existSite) {
                if(trim($existSite) == trim($site)){
                    return Whitelist::SITE_ALREADY_EXIST;
                }
            }
        }
        try {
            $fp = fopen(SQUID_WHITELIST, 'a+');
            if (\filter_var($site, FILTER_VALIDATE_DOMAIN)) {
                $file = \file(SQUID_WHITELIST);
                $lineCount = \count($file);
                for ($i = 1; $i < \count($file); $i++) {
                    $line = \substr($file[$i], 1, strlen($file[$i]));
                    if (trim($line) == $categorie) {
                        $lineCount = $i;
                        break;
                    }
                }
                array_splice($file, $lineCount + 1, 0, $site);
                \file_put_contents(SQUID_WHITELIST, "");
                for ($i = 0; $i < \count($file); $i++) {
                    fwrite($fp, trim($file[$i]) . "\r\n");
                }
            } else {
                return self::DOMAIN_FORMAT_INVALID;
            }
            fclose($fp);
            return self::SITE_ADDED;
        } catch (\Exception $th) {
            return Whitelist::ADDING_SITE_ERROR;
        }
    }

    public static function remove($site)
    {
        $file = file(SQUID_WHITELIST);
        for ($i = 1; $i < \count($file); $i++) { 
            if(trim($file[$i]) == trim($site)){
                array_splice($file, $i, 1);
                file_put_contents(SQUID_WHITELIST, implode($file));
                return true;
            }
        }
        return false;
    }

    public static function addCategorie($categorie)
    {
        $categories = Whitelist::getCategories();
        $insert = true;
        for ($i = 0; $i < \count($categories); $i++) {
            if (trim(strtolower($categories[$i])) == trim(strtolower($categorie))) {
                $insert = false;
                break;
            }
        }

        if ($insert) {
            \file_put_contents(SQUID_WHITELIST, "\r\n#" . $categorie, FILE_APPEND);
            $file = \file(SQUID_WHITELIST);
            $firstLine = trim($file[0]);
            $firstLine .= ";$categorie\r\n";
            $file[0] = $firstLine;
            file_put_contents(SQUID_WHITELIST, implode($file));
        }
    }

    public static function deleteCategoria($categoria)
    {
        $delete = true;
        $file = file(SQUID_WHITELIST);
        $file[0] = str_replace("#" . $categoria . ";", "#", $file[0]);
        $file[0] = str_replace(";" . $categoria, "", $file[0]);
        $file[0] = str_replace($categoria, "", $file[0]);
        for ($i = 1; $i < \count($file); $i++) {
            if ($file[$i]{
                0} == "#") {
                $subString = \substr($file[$i], 1, \strlen($file[$i]));
                if (strtolower(trim($subString)) == strtolower(trim($categoria))) {
                    array_splice($file, $i, 1);
                    while ($delete) {
                        if (isset($file[$i]) && $file[$i]{
                        0} != "#") {
                            array_splice($file, $i, 1);
                        } else {
                            file_put_contents(SQUID_WHITELIST, implode($file));
                            $delete = false;
                        }
                    }
                    break;
                }
            }
        }
    }

    public static function reloadProxy()
    {
        shell_exec('sudo squid -k reconfigure');
        echo "<script>alert('Proxy squid reconfigured.')</script>";
    }
}
