<?php
include "open-connection.php";
session_start();

$error_message = ""; // Inicializar el mensaje de error

if (!empty($_POST)) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = '<div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
        <div class="alert-icon">
            <i class="far fa-fw fa-bell"></i>
        </div>
        <div class="alert-message">
            <strong>Alerta!</strong> No puedes iniciar sesión con los campos vacíos.
        </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>';
    } else {
        // Recepcionando datos del formulario.
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Consulta preparada para evitar la inyección SQL
        $query = "SELECT id_usuario, imagen, nombres, apellidos, rol_id, contrasena FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($open_connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Verificar la contraseña cifrada usando password_verify
            if (password_verify($password, $row['contrasena'])) {
                // Almacenar variables en la sesión
                $_SESSION['active'] = true;
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['imagen'] = $row['imagen'];
                $_SESSION['nombres'] = $row['nombres'];
                $_SESSION['apellidos'] = $row['apellidos'];
                $_SESSION['rol_id'] = $row['rol_id'];
                header("Location: ../index.php");
                exit();
            } else {
                $error_message = '<div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>Alerta!</strong> Correo o contraseña incorrectos.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>';
            }
        } else {
            $error_message = '<div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>Alerta!</strong> Correo o contraseña incorrectos.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>';
        }
    }
}
include "close-connection.php";
?>