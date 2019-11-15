<?php

/**
 * File php che si occupa di istanziare tutte le variabili globali.
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('URL', 'http://10.20.129.99/');

define('DB_USER', 'root');
define('DB_PASS', 'Password&1');
define('DB_HOST', '10.20.129.99');
define('DB_NAME', 'gestioneWhitelist');
define('DB_PORT', 3307);
define('SQLITE', null);


define('SQUID_WHITELIST', "/etc/squid/whitelist.acl");

$autoload_directories = array(
    "application/controllers/",
    "application/libs/",
    "application/models/"
);
