<?php

/**
 * File php che si occupa di istanziare tutte le variabili globali.
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('URL', 'http://localhost:8080/');

define('SQLITE', 'application/databases/GestioneWhitelist.db');


define('SQUID_WHITELIST', "/etc/squid/whitelist.acl");
define('CATEGORIE_DELIMITER', ";");


$autoload_directories = array(
    "application/controllers/",
    "application/libs/",
    "application/models/"
);

