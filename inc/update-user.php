<?php

include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Recibiendo datos del formulario.
$id = isset($_POST['id']) ? $_POST['id'] : '';
$dni = isset($_POST['dni']) ? $_POST['dni'] : '';
$nombres = isset($_POST['nombres']) ? $_POST['nombres'] : '';
$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
$movil = isset($_POST['movil']) ? $_POST['movil'] : '';
$email = isset($_POST['correo']) ? $_POST['correo'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$rol = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$imagen = isset($_FILES['imagen']['tmp_name']) ? $_FILES['imagen']['tmp_name'] : '';

// Verificar si el DNI o correo electrónico ya están registrados para otro usuario
$query_duplicate = "SELECT id_usuario FROM usuarios WHERE (dni = ? OR email = ?) AND id_usuario != ?";
$stmt_duplicate = mysqli_prepare($open_connection, $query_duplicate);
mysqli_stmt_bind_param($stmt_duplicate, "ssi", $dni, $email, $id);
mysqli_stmt_execute($stmt_duplicate);
mysqli_stmt_store_result($stmt_duplicate);
$num_rows = mysqli_stmt_num_rows($stmt_duplicate);

if ($num_rows > 0) {
    // Ya existe otro usuario con el mismo DNI o correo electrónico
    echo '
        <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                <strong>Error!</strong> Ya existe otro usuario con el mismo DNI o correo electrónico.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
    include "close-connection.php";
    exit(); // Detiene la ejecución del script
}

// Si no se ha enviado una imagen en el formulario, se mantiene la misma de la base de datos.
if (empty($imagen)) {
    $query = "SELECT imagen FROM usuarios WHERE id_usuario = ?";
    $stmt = mysqli_prepare($open_connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $db_image);
    mysqli_stmt_fetch($stmt);
    $default_image_path = $db_image;
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
        $default_image_path = '';
    }
}

// Obtener la contraseña actual del usuario desde la base de datos
$query = "SELECT contrasena FROM usuarios WHERE id_usuario = ?";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $db_password);
mysqli_stmt_fetch($stmt);

// Si se proporcionó una nueva contraseña, se encripta con bcrypt, de lo contrario, se mantiene la contraseña actual
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
} else {
    $hashed_password = $db_password;
}

// Actualizando datos del usuario.
$query = "UPDATE usuarios SET imagen = ?, dni = ?, nombres = ?, apellidos = ?, movil = ?, email = ?, direccion = ?, contrasena = ?, rol_id = ? WHERE id_usuario = ?";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, "sssssssssi", $default_image_path, $dni, $nombres, $apellidos, $movil, $email, $direccion, $hashed_password, $rol, $id);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo '
        <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                <strong>Alerta!</strong> Usuario actualizado correctamente.
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
                <strong>Alerta!</strong> Error al actualizar usuario.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
}

include "close-connection.php";

?>
