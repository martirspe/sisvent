<?php
include "open-connection.php";

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    // Consulta para buscar el cliente en la base de datos
    $query = "SELECT nombres, apellidos, direccion FROM usuarios WHERE dni = '$dni'";
    $result = mysqli_query($open_connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response = [
            'success' => true,
            'nombres' => $row['nombres'],
            'apellidos' => $row['apellidos'],
            'direccion' => $row['direccion']
        ];
    } else {
        $response = ['success' => false];
    }

    echo json_encode($response);
}

include "close-connection.php";
?>
