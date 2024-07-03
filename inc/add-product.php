<?php
include "open-connection.php";

// Verificar si se recibió una imagen en el formulario
if(isset($_FILES['imagen']['tmp_name']) && !empty($_FILES['imagen']['tmp_name'])) {
    $imagen = $_FILES['imagen']['tmp_name'];
    $image_extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $image_name = 'img-' . date('dmY-His') . '.' . $image_extension;
    $route = $_SERVER['DOCUMENT_ROOT'] . '/sisvent/img/products/' . $image_name;
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
$cantidad = mysqli_real_escape_string($open_connection, $_POST['cantidad'] ?? '');
$precio = mysqli_real_escape_string($open_connection, $_POST['precio'] ?? '');

// Verificando si el producto ya está registrado.
$query = "SELECT codigo FROM productos WHERE codigo = ?";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, "s", $codigo);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo '
        <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                El producto ya está registrado.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
    include "close-connection.php";
    exit(); // Detiene la ejecución del script
}

// Insertar datos en la tabla "Productos"
$query = "INSERT INTO productos (imagen, nombre, marca_id, modelo, codigo, color, descripcion, categoria_id, cantidad, precio) 
          VALUES ('$route_xampp', '$nombre', '$marca', '$modelo', '$codigo', '$color', '$descripcion', '$categoria', '$cantidad', '$precio')";
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
                Producto guardado correctamente.
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
                Error al guardar producto.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
}

include "close-connection.php"; 
?>
