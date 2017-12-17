<?php

session_start();

if (!isset($_SESSION['user'])){
    header("Location: web/login.php");
}

echo "<h>Strona główna</h>";

echo"<br><a href='web/login.php?action=logout'>Wyloguj się</a>";
