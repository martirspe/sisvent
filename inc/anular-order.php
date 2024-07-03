<?php
include "open-connection.php";
session_start();

if (isset($_GET['id'])) {
    $idVenta = $_GET['id'];

    // Eliminar los detalles de la venta
    $query = "DELETE FROM detalle_ventas WHERE venta_id = '$idVenta'";
    $result = mysqli_query($open_connection, $query);

    if ($result) {
        // Eliminar la venta
        $query = "DELETE FROM ventas WHERE id_venta = '$idVenta'";
        $result = mysqli_query($open_connection, $query);

        if ($result) {
            $_SESSION['message'] = "Venta anulada exitosamente.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error al anular la venta.";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Error al eliminar los detalles de la venta.";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: ../all-orders.php");
    exit();
}

include "close-connection.php";
?>
