<?php
include "inc/open-connection.php";
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
                header("Location: index.php");
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
                            <h1 class="h2">Bienvenido</h1>
                            <p class="lead">
                                Inicia sesión con tu cuenta para continuar.
                            </p>
                        </div>

                        <div class="mt-3"><?php echo $error_message; ?></div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-4">
                                    <div class="text-center">
                                        <?php 
                                            $last_user_image = isset($_SESSION['imagen']) ? $_SESSION['imagen'] : 'img/default/user.png';
                                        ?>
                                        <img src="<?php echo $last_user_image; ?>" alt=""
                                            class="img-fluid rounded-circle" width="120" height="120" />
                                    </div>
                                    <form id="login" action="login.php" method="POST">
                                        <div class="form-group">
                                            <label>Correo electrónico</label>
                                            <input class="form-control form-control-lg" type="email" name="email"
                                                placeholder="Ingresa tu correo electrónico" />
                                        </div>
                                        <div class="form-group">
                                            <label>Contraseña</label>
                                            <input class="form-control form-control-lg" type="password" name="password"
                                                placeholder="Ingresa tu contraseña" />
                                            <small>
                                                <a href="reset-password.php">Olvidé mi contraseña</a>
                                            </small>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Iniciar Sesión</button>
                                        </div>
                                        <p class="mt-4">
                                            ¿No estas registrado? <a href="register.php">Crea una cuenta</a>
                                        </p>
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