<?php

// Incluir archivo de conexión
include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recepción de datos del formulario
    $id = $_POST['id_producto'];
    $nombre = trim($_POST['nombre']);
    $codigo = trim($_POST['codigo']);
    $modelo = trim($_POST['modelo']);
    $color = trim($_POST['color']);
    $marca_id = $_POST['marca'];
    $descripcion = trim($_POST['descripcion']);
    $precio = $_POST['precio'];
    $categoria_id = $_POST['categoria'];

    $route_xampp = '';

    // Si se ha enviado una imagen en el formulario
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_name = 'img-' . date('dmY-His') . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $upload_directory = $_SERVER['DOCUMENT_ROOT'] . '/sispro/img/products/';
        $route = $upload_directory . $imagen_name;
        $route_xampp = 'img/products/' . $imagen_name;

        // Mover la imagen del formulario al directorio correcto
        move_uploaded_file($imagen_tmp, $route);
    } else {
        // Si no se ha enviado una imagen en el formulario, se asigna la misma de la base de datos
        $stmt = $open_connection->prepare("SELECT imagen FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $route_xampp = $row['imagen'];
    }

    // Actualizar el producto en la base de datos
    $stmt = $open_connection->prepare("UPDATE productos 
              SET imagen=?, nombre=?, codigo=?, modelo=?, color=?, marca_id=?, descripcion=?, precio=?, categoria_id=? 
              WHERE id_producto=?");
    $stmt->bind_param("sssssisdis", $route_xampp, $nombre, $codigo, $modelo, $color, $marca_id, $descripcion, $precio, $categoria_id, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Éxito al actualizar el producto
        echo '
            <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>¡Éxito!</strong> Producto actualizado correctamente.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } else {
        // No se han hecho cambios en el producto
        echo '
            <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>¡Alerta!</strong> No se han realizado cambios en el producto.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar conexión
include "close-connection.php";
?>
