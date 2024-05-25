<?php

// Incluir archivo de conexión
include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Recepción de datos del formulario
$id_producto = $_POST['id_producto'];
$nombre = trim($_POST['nombre']);
$codigo = trim($_POST['codigo']);
$modelo = trim($_POST['modelo']);
$color = trim($_POST['color']);
$marca_id = $_POST['marca'];
$descripcion = trim($_POST['descripcion']);
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$categoria_id = $_POST['categoria'];

// Si existe imagen en el formulario le asigna la ruta y un nuevo nombre.
$imagen = $_FILES['imagen']['tmp_name'];
$nameimg = 'img-' . date('dmY-His', time()) . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
$route = $_SERVER['DOCUMENT_ROOT'] . '/sisvent/img/products/';
$route = $route . $nameimg;

// Ruta relativa para XAMPP
$route_xampp = 'img/products/' . $nameimg;

// Verificando si el producto ya está registrado con un código diferente al que se está editando.
$query = "SELECT id_producto FROM productos WHERE codigo = ? AND id_producto != ?";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, "si", $codigo, $id_producto);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo '
        <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                El producto con este código ya está registrado.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
    include "close-connection.php";
    exit(); // Detiene la ejecución del script
}

// Si no existe imagen en el formulario, se le asigna la misma de la base de datos.
if (empty($imagen)) {
    $query = "SELECT imagen FROM productos WHERE id_producto = '$id_producto'";
    $results = mysqli_query($open_connection, $query);
    if ($row = mysqli_fetch_array($results)) {
        $route_xampp = $row['imagen'];
    }
}

// Actualizar el producto en la base de datos
$stmt = $open_connection->prepare("UPDATE productos 
          SET imagen=?, nombre=?, codigo=?, modelo=?, color=?, marca_id=?, descripcion=?, cantidad=?, precio=?, categoria_id=? 
          WHERE id_producto=?");
$stmt->bind_param("sssssissdis", $route_xampp, $nombre, $codigo, $modelo, $color, $marca_id, $descripcion, $cantidad, $precio, $categoria_id, $id_producto);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Éxito al actualizar el producto
    echo '
        <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                Producto actualizado correctamente.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';

    // Si se ha enviado una imagen en el formulario, moverla al directorio correcto
    if (!empty($imagen)) {
        if (!move_uploaded_file($imagen, $route)) {
            echo '
                <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        No se pudo mover la imagen al directorio de destino.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        }
    }
} else {
    // No se han hecho cambios en el producto
    echo '
        <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                No se han realizado cambios en el producto.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
}

// Cerrar conexión
include "close-connection.php";
?>
