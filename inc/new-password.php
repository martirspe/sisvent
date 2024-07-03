<?php

// Incluir archivo de conexión
include "open-connection.php";

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Verificar si se ha enviado el formulario de restablecimiento de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'], $_POST['password2'], $_POST['token'], $_POST['email'], $_POST['hash'])) {
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $token = $_POST['token'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $hash = $_POST['hash'];

    // Validaciones
    if ($password !== $password2) {
        echo '
            <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    Las contraseñas ingresadas no coinciden.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '
            <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    La dirección de correo electrónico no es válida.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } else {
        // Verificar la autenticidad del token
        $secret_key = 'albz9133@PWD';
        $calculated_hash = hash_hmac('sha256', $token, $secret_key);

        if (hash_equals($calculated_hash, $hash)) {
            // Cifrar la nueva contraseña con bcrypt
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $query_update_password = "UPDATE usuarios SET contrasena = ? WHERE email = ?";
            $stmt_update_password = mysqli_prepare($open_connection, $query_update_password);
            mysqli_stmt_bind_param($stmt_update_password, "ss", $hashed_password, $email);
            $update_result = mysqli_stmt_execute($stmt_update_password);

            if ($update_result) {
                echo '
                    <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                        <div class="alert-icon">
                            <i class="far fa-fw fa-bell"></i>
                        </div>
                        <div class="alert-message">
                            Tu contraseña ha sido restablecida exitosamente.
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
                            Error al restablecer la contraseña. Por favor, intenta de nuevo.
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                ';
            }
        } else {
            echo '
                <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        El enlace de restablecimiento de contraseña no es válido o ha expirado.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        }
    }
} else {
    echo '
        <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                Solicitud no válida.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
}

include "close-connection.php";
?>
