<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('URL', 'http://127.0.0.1:8080/');

define('DB_USER', 'root');
define('DB_PASS', 'Password&1');
define('DB_HOST', '10.20.143.220');
define('DB_NAME', 'gestioneWhitelist');
define('DB_PORT', 3307);
define('SQLITE', null);

$autoload_directories = array(
    "application/controllers/",
    "application/libs/",
    "application/models/"
);
