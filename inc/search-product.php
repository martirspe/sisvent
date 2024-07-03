<?php
include "open-connection.php";

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Consulta para buscar los productos en la base de datos
    $sql = "SELECT codigo, nombre, precio FROM productos WHERE nombre LIKE '%$query%'";
    $result = mysqli_query($open_connection, $sql);

    $productos = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    }

    echo json_encode($productos);
}

include "close-connection.php";
?>
