<?php

include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Recepcionando datos del formulario.
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

// Verificar si se proporcionó una imagen y procesarla si es el caso
if (isset($_FILES['imagen']['tmp_name']) && !empty($_FILES['imagen']['tmp_name'])) {
    $imagen = $_FILES['imagen']['tmp_name'];
    $image_extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nameimg = 'img-' . date('dmY-His') . '.' . $image_extension;
    $route = $_SERVER['DOCUMENT_ROOT'] . '/sispro/img/products/category/' . $nameimg;
    $route_xampp = 'img/products/category/' . $nameimg;

    // Mueve la imagen del formulario al directorio correcto.
    move_uploaded_file($imagen, $route);
} else {
    // Si no se proporcionó una imagen, asignar una por defecto.
    $route_xampp = 'img/default/category.png';
}

// Verificando que la categoría ingresada en el formulario no esté registrada.
$query = "SELECT nombre FROM categorias WHERE nombre = ?";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, 's', $nombre);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo '
        <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                <strong>Alerta!</strong> Ya existe una categoría con el mismo nombre.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
} else {
    // Insertar la nueva categoría en la base de datos.
    $query = "INSERT INTO categorias (imagen, nombre, descripcion, estado) VALUES (?, ?, ?, 1)";
    $stmt = mysqli_prepare($open_connection, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $route_xampp, $nombre, $descripcion);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        echo '
            <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>Alerta!</strong> Categoría guardada correctamente.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } else {
        echo '
            <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>Alerta!</strong> Error al guardar categoría.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    }
}

include "close-connection.php"; 
?>
