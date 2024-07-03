<?php
// Incluir archivo de conexión
include "inc/open-connection.php";

// Iniciar sesión
session_start();

$error_message = ""; // Inicializar el mensaje de error

// Verificar si se recibieron los parámetros necesarios
if (isset($_GET['token'], $_GET['hash'], $_GET['email'])) {
    $token = $_GET['token'];
    $hash = $_GET['hash'];
    $email = $_GET['email'];

    // Verificar si el token y el hash son válidos (comparar con los almacenados en la base de datos o sesión)
    $secret_key = 'albz9133@PWD'; // Tu clave secreta (debe coincidir con la utilizada en reset-password.php)
    $expected_hash = hash_hmac('sha256', $token, $secret_key);

    if ($hash === $expected_hash) {
        // Si el token y el hash son válidos, procesar el formulario de nueva contraseña
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verificar si las contraseñas coinciden
            if ($_POST['password'] === $_POST['confirm_password']) {
                // Actualizar la contraseña en la base de datos
                $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $query = "UPDATE usuarios SET contrasena = ? WHERE email = ?";
                $stmt = mysqli_prepare($open_connection, $query);
                mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $email);
                
                if (mysqli_stmt_execute($stmt)) {
                    // Contraseña actualizada con éxito
                    $error_message = '
                        <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                            <div class="alert-icon">
                                <i class="far fa-fw fa-bell"></i>
                            </div>
                            <div class="alert-message">
                                Contraseña restablecida exitosamente. Ahora puedes iniciar sesión.
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    ';
                    // Opcional: Redirigir al usuario a la página de inicio de sesión después de un breve retraso
                    header("refresh:3;url=login.php"); 
                    exit();
                } else {
                    $error_message = '
                        <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                            <div class="alert-icon">
                                <i class="far fa-fw fa-bell"></i>
                            </div>
                            <div class="alert-message">
                                Error al actualizar la contraseña.
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    ';
                }
            } else {
                $error_message = '
                    <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                        <div class="alert-icon">
                            <i class="far fa-fw fa-bell"></i>
                        </div>
                        <div class="alert-message">
                            Las contraseñas no coinciden.
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                ';
            }
        }
    } else {
        $error_message = '
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
} else {
    $error_message = '
        <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                Faltan parámetros.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
}

include "inc/close-connection.php";
?>

<?php include "inc/header.php"; ?>

<body>
    <main class="main h-100 w-100">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mt-4">
                            <h1 class="h2">Restablecer contraseña</h1>
                        </div>
                        <div class="mt-3"><?php echo $error_message; ?></div>
                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-4">
                                    <form id="new-password" action="" method="POST">
                                        <div class="form-group">
                                            <label>Nueva contraseña</label>
                                            <input class="form-control form-control-lg" type="password" name="password"
                                                placeholder="Nueva contraseña" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Confirmar nueva contraseña</label>
                                            <input class="form-control form-control-lg" type="password"
                                                name="confirm_password" placeholder="Confirmar nueva contraseña"
                                                required />
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Restablecer
                                                contraseña</button>
                                            <a href="login.php" class="btn btn-lg btn-secondary">Regresar</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "inc/scripts.php"; ?>
</body>

</html>