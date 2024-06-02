<?php 

if ( defined('APP_DB_HOST') && defined("APP_DB_NAME") && defined("APP_DB_USER") && defined("APP_DB_PASSWORD")) {
	global $db;

    $mysqli = new mysqli(APP_DB_HOST, APP_DB_USER, APP_DB_PASSWORD, APP_DB_NAME); 

    if ($mysqli->connect_error) { 
        echo '{"error": "' . $mysqli->connect_error . '"}';
        exit();
    } 

    $db = $mysqli;

}