<?php
include "open-connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $fecha = $_POST['fecha'];
    $subtotal = $_POST['subtotal'];
    $igv = $_POST['igv'];
    $total = $_POST['total'];
    $productos = json_decode($_POST['productos'], true);

    // Obtener el ID del cliente a partir del DNI
    $query = "SELECT id_usuario FROM usuarios WHERE dni = '$dni'";
    $result = mysqli_query($open_connection, $query);
    $row = mysqli_fetch_assoc($result);
    $cliente_id = $row['id_usuario'];

    // Insertar la venta
    $query = "INSERT INTO ventas (fecha_venta, total, usuario_id, estado) VALUES ('$fecha', '$total', '$cliente_id', 1)";
    $result = mysqli_query($open_connection, $query);

    if ($result) {
        $venta_id = mysqli_insert_id($open_connection);

        // Insertar los detalles de la venta
        foreach ($productos as $producto) {
            $codigo_producto = $producto['codigo'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];
            $subtotal = $producto['subtotal'];

            // Obtener el ID del producto a partir del código
            $query = "SELECT id_producto FROM productos WHERE codigo = '$codigo_producto'";
            $result = mysqli_query($open_connection, $query);
            $row = mysqli_fetch_assoc($result);
            $producto_id = $row['id_producto'];

            $query = "INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) 
                      VALUES ('$venta_id', '$producto_id', '$cantidad', '$precio', '$subtotal')";
            mysqli_query($open_connection, $query);
        }

        $_SESSION['message'] = "Venta registrada exitosamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al registrar la venta.";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: ../all-orders.php");
    exit();
}

include "close-connection.php";
?>
