<?php

include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Recibiendo datos del formulario.
$dni = isset($_POST['dni']) ? $_POST['dni'] : '';
$nombres = isset($_POST['nombres']) ? $_POST['nombres'] : '';
$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
$movil = isset($_POST['movil']) ? $_POST['movil'] : '';
$email = isset($_POST['correo']) ? $_POST['correo'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$rol = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$imagen = isset($_FILES['imagen']['tmp_name']) ? $_FILES['imagen']['tmp_name'] : '';

// Verificando si el usuario ya está registrado.
$query = "SELECT dni, email FROM usuarios WHERE dni = ? OR email = ?";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, "ss", $dni, $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo '
        <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                El usuario ya está registrado.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
    include "close-connection.php";
    exit(); // Detiene la ejecución del script
}

// Si no se ha enviado una imagen en el formulario, se asigna una por defecto.
if (empty($imagen)) {
    $default_image_path = 'img/default/user.png';
} else {
    $image_extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $image_name = 'img-' . date('dmY-His') . '.' . $image_extension;
    $image_destination = $_SERVER['DOCUMENT_ROOT'] . '/sisvent/img/users/' . $image_name;

    // Establecer permisos de escritura en el directorio de destino
    if (!file_exists(dirname($image_destination))) {
        // Si el directorio no existe, intenta crearlo
        mkdir(dirname($image_destination), 0777, true); // Establece los permisos a 0777
    }

    // Mueve la imagen del formulario al directorio correcto.
    if (move_uploaded_file($imagen, $image_destination)) {
        $default_image_path = 'img/users/' . $image_name;
    } else {
        $default_image_path = 'img/default/user.png';
    }
}
    
// Encripta la contraseña con bcrypt
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO usuarios (imagen, dni, nombres, apellidos, movil, email, direccion, contrasena, rol_id)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, "ssssssssi", $default_image_path, $dni, $nombres, $apellidos, $movil, $email, $direccion, $hashed_password, $rol);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo '
        <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                Usuario guardado correctamente.
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
                Error al guardar usuario.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
}

include "close-connection.php";

?>