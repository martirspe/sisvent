<?php
include "open-connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idVenta = $_POST['id_venta'];
    $subtotal = $_POST['subtotal'];
    $igv = $_POST['igv'];
    $total = $_POST['total'];
    $productos = json_decode($_POST['productos'], true);

    // Actualizar la venta
    $query = "UPDATE ventas SET total = '$total' WHERE id_venta = '$idVenta'";
    $result = mysqli_query($open_connection, $query);

    if ($result) {
        // Eliminar los detalles de la venta existentes
        $query = "DELETE FROM detalle_ventas WHERE venta_id = '$idVenta'";
        mysqli_query($open_connection, $query);

        // Insertar los nuevos detalles de la venta
        foreach ($productos as $producto) {
            $codigo_producto = $producto['codigo'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio_unitario'];
            $subtotal = $producto['cantidad'] * $producto['precio_unitario'];

            // Obtener el ID del producto a partir del código
            $query = "SELECT id_producto FROM productos WHERE codigo = '$codigo_producto'";
            $result = mysqli_query($open_connection, $query);
            $row = mysqli_fetch_assoc($result);
            $producto_id = $row['id_producto'];

            $query = "INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) 
                      VALUES ('$idVenta', '$producto_id', '$cantidad', '$precio', '$subtotal')";
            mysqli_query($open_connection, $query);
        }

        $_SESSION['message'] = "Venta actualizada exitosamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al actualizar la venta.";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: ../all-orders.php");
    exit();
}

include "close-connection.php";
?>