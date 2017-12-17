<?php

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DBNAME', 'twitter');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DBNAME . ";charset=utf8", DB_USER, DB_PASSWORD);
} catch (PDOException $ex) {
    echo "Błąd połączenia: " . $ex->getMessage();
}