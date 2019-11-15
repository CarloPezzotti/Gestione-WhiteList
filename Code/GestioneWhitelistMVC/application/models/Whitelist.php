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



    public static function get()
    {
        if (file_exists(SQUID_WHITELIST)) {
            if (is_readable(SQUID_WHITELIST) || \is_writable(SQUID_WHITELIST)) {
                return file(SQUID_WHITELIST);
            } else {
                throw new Exception("Permission denied", self::PERMISSION_DENIED);
            }
        } else {
            throw new Exception("File not exist", self::FILE_NOT_EXIST);
        }
    }

    public static function add($site)
    {
        try {
            $files = self::get();
            for ($i = 0; $i < \count($files); $i++) {
                if (\trim($files[$i]) == trim($site)) {
                    return self::SITE_ALREADY_EXIST;
                }
            }
            $fp = fopen(SQUID_WHITELIST, 'a');
            if (\filter_var($site, FILTER_VALIDATE_DOMAIN)) {
                $site = trim($site);
                $site .= "\r\n";
                if (!fwrite($fp, $site)) {
                    return self::ADDING_SITE_ERROR;
                }
            } else {
                return self::DOMAIN_FORMAT_INVALID;
            }
            fclose($fp);
            return self::SITE_ADDED;
        } catch (\Exception $th) {
            throw $th;
        }
    }                           

    public static function remove($site)
    {
        $sites = self::get();
        for ($i = 0; $i < \count($sites); $i++) {
            if (trim($sites[$i]) == trim($site)) {
                $contents = file_get_contents(SQUID_WHITELIST);
                $contents = str_replace($sites[$i], '', $contents);
                file_put_contents(SQUID_WHITELIST, $contents);
                break;
            }
        }
    }


    public static function reloadProxy(){
        shell_exec('sudo squid -k reconfigure');
        echo "<script>alert('Proxy squid reconfigured.')</script>";
    }
}
