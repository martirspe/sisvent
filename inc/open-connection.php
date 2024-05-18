<?php

$hostname = "localhost";
$username = "root";
$password = "";
$db       = "integrador_dos";

$open_connection = new mysqli($hostname, $username, $password)
or die("No es posible conectarse a MySQL");

mysqli_select_db($open_connection, $db)
or die("Base de datos no disponible");

if (mysqli_connect_errno()) {
    echo "No es posible conectarse a MySQL";
    exit;
}

mysqli_set_charset($open_connection, "utf8");