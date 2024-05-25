<?php
include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Recepcionando datos del formulario.
$img_user = 'img/default/user.png';
$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$movil = $_POST['movil'];
$email = $_POST['correo'];
$direccion = $_POST['direccion'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$rol = 2;

// Validación de datos
if (empty($dni) || empty($nombres) || empty($apellidos) || empty($movil) || empty($email) || empty($direccion) || empty($password) || empty($password2)) {
    echo '<div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
        <div class="alert-icon">
            <i class="far fa-fw fa-bell"></i>
        </div>
        <div class="alert-message">
            Todos los campos son obligatorios.
        </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>';
} elseif ($password !== $password2) {
    echo '<div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
        <div class="alert-icon">
            <i class="far fa-fw fa-bell"></i>
        </div>
        <div class="alert-message">
            Las contraseñas ingresadas no coinciden.
        </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
        <div class="alert-icon">
            <i class="far fa-fw fa-bell"></i>
        </div>
        <div class="alert-message">
            La dirección de correo electrónico no es válida.
        </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>';
} else {
    // Verificar si el usuario ya está registrado por correo electrónico o dni
    $query = "SELECT * FROM usuarios WHERE email = ? OR dni = ?";
    $stmt = mysqli_prepare($open_connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $dni);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo '<div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                El usuario ya está registrado.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>';
    } else {
        // Cifrar la contraseña con bcrypt
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Consulta preparada para evitar la inyección SQL
        $query = "INSERT INTO usuarios (imagen, dni, nombres, apellidos, movil, email, direccion, contrasena, rol_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($open_connection, $query);
        mysqli_stmt_bind_param($stmt, 'ssssssssi', $img_user, $dni, $nombres, $apellidos, $movil, $email, $direccion, $hashed_password, $rol);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo '<div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                Usuario registrado correctamente.
            </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>';
        } else {
            echo '<div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                Error al registrar usuario.
            </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>';
        }
    }
}

include "close-connection.php";
?>