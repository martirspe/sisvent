<?php

include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Si existe imagen en el formulario le asigan la ruta y un nuevo nombre.
$imagen = $_FILES['imagen']['tmp_name'];
$nameimg = 'img-' . date('dmY-His', time()) . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION); 
$route = $_SERVER['DOCUMENT_ROOT'] . '/sisvent/img/products/categories/';
$route = $route.$nameimg;

/* Cuando se trabaja en XAMPP */
$route_xampp = 'img/products/categories/';
$route_xampp = $route_xampp.$nameimg;

// Recepcionando datos del fomulario.
$id_categoria = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

//Si no existe imagen en el formulario, se le asigna el mismo de la base de datos.
if (empty($imagen)) {
    $query = "SELECT imagen FROM categorias WHERE id_categoria = '$id_categoria'";
    $results = mysqli_query($open_connection, $query);
    while ($row = mysqli_fetch_array($results)) {
        $route_xampp = $row['imagen'];
    }
    $query = "UPDATE categorias SET imagen='$route_xampp', nombre='$nombre', descripcion='$descripcion' WHERE id_categoria='$id_categoria'";
    $results = mysqli_query($open_connection, $query);
    if ($results == false) {
        echo '
            <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    Error al actualizar categoría.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } else {
        if (mysqli_affected_rows($open_connection) == 0) {
            echo '
                <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        No has hecho cambios para esta categoría.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        Categoría actualizada correctamente.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        }
    }
} else {
    $query = "UPDATE categorias SET imagen='$route_xampp', nombre='$nombre', descripcion='$descripcion' WHERE id_categoria='$id_categoria'";
    $results = mysqli_query($open_connection, $query);

    if ($results == false) {
        echo '
            <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    Error al actualizar categoría.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } else {
        if (mysqli_affected_rows($open_connection) == 0) {
            echo '
                <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        No has hecho cambios para esta categoría.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        Categoría actualizada correctamente.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
            // Mueve la imagen del formulario al directorio correcto.
            move_uploaded_file($imagen,$route);
        }
    }
}

include "close-connection.php"; ?>