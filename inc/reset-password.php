<?php

// Incluir archivo de conexión
include "open-connection.php";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibió un correo electrónico
    if (isset($_POST['email'])) {
        // Filtrar y limpiar el correo electrónico
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Generar un token único
        $token = bin2hex(random_bytes(32));

        // Verificar si el correo electrónico existe en la base de datos
        $query = "SELECT id_usuario FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($open_connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Si el correo electrónico existe, almacenar el token en la base de datos
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $query_update_token = "UPDATE usuarios SET reset_token = ? WHERE email = ?";
            $stmt_update_token = mysqli_prepare($open_connection, $query_update_token);
            mysqli_stmt_bind_param($stmt_update_token, "ss", $token, $email);
            mysqli_stmt_execute($stmt_update_token);

            // Envía el correo electrónico con el enlace de restablecimiento de contraseña
            $reset_link = "http://localhost/reset-password.php?token=" . $token;
            $subject = "Restablecimiento de contraseña";
            $message = "Hola, para restablecer tu contraseña, haz clic en el siguiente enlace: <a href='$reset_link'>$reset_link</a>";
            $headers = "From: rosonoem@gmail.com" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // Envía el correo electrónico
            mail($email, $subject, $message, $headers);

            // Mostrar un mensaje de éxito
            echo '
                <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        Se ha enviado un correo electrónico con instrucciones para restablecer tu contraseña.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        } else {
            // Si no se encuentra el correo electrónico en la base de datos, mostrar un mensaje de error
            echo '
                <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        El correo electrónico proporcionado no está registrado en nuestro sistema.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        }
    } else {
        // Si no se proporcionó un correo electrónico, mostrar un mensaje de error
        echo '
            <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    Por favor, introduce tu correo electrónico.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    }
}

?>