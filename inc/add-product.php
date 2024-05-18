<?php
include "open-connection.php";

// Verificar si se recibió una imagen en el formulario
if(isset($_FILES['imagen']['tmp_name']) && !empty($_FILES['imagen']['tmp_name'])) {
    $imagen = $_FILES['imagen']['tmp_name'];
    $image_extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $image_name = 'img-' . date('dmY-His') . '.' . $image_extension;
    $route = $_SERVER['DOCUMENT_ROOT'] . '/sispro/img/products/' . $image_name;
    $route_xampp = 'img/products/' . $image_name;
} else {
    // Si no se recibió una imagen, asignar una por defecto
    $route_xampp = 'img/default/product.png';
}

// Obtener datos del formulario de manera segura
$nombre = mysqli_real_escape_string($open_connection, $_POST['nombre'] ?? '');
$marca = mysqli_real_escape_string($open_connection, $_POST['marca'] ?? '');
$modelo = mysqli_real_escape_string($open_connection, $_POST['modelo'] ?? '');
$codigo = mysqli_real_escape_string($open_connection, $_POST['codigo'] ?? '');
$color = mysqli_real_escape_string($open_connection, $_POST['color'] ?? '');
$descripcion = mysqli_real_escape_string($open_connection, $_POST['descripcion'] ?? '');
$categoria = mysqli_real_escape_string($open_connection, $_POST['categoria'] ?? '');
$precio = mysqli_real_escape_string($open_connection, $_POST['precio'] ?? '');

// Insertar datos en la tabla "Productos"
$query = "INSERT INTO productos (imagen, nombre, marca_id, modelo, codigo, color, descripcion, categoria_id, precio) 
          VALUES ('$route_xampp', '$nombre', '$marca', '$modelo', '$codigo', '$color', '$descripcion', '$categoria', '$precio')";
$results = mysqli_query($open_connection, $query);

if ($results) {
    // Mover la imagen al directorio correcto si se recibió una
    if(isset($imagen)) {
        move_uploaded_file($imagen, $route);
    }

    echo '
        <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                <strong>Alerta!</strong> Producto guardado correctamente.
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
                <strong>Alerta!</strong> Error al guardar producto.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
}

include "close-connection.php"; 
?>
