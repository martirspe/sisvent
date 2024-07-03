<?php

// Incluir archivo de conexión
include "inc/open-connection.php";

// Iniciar sesión
session_start();

// Verificar si el usuario está activo
if (!empty($_SESSION['active'])) {
    header("location: index.php");
	exit(); // Asegura que el script se detenga después de redirigir
}

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
                            <p class="lead">
                                Ingrese su correo electrónico para restablecer su contraseña.
                            </p>
                        </div>

                        <div class="mt-3" id="message"></div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-4">
                                    <form id="reset-password" action="inc/reset-password.php" method="POST">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control form-control-lg" type="email" name="email"
                                                placeholder="Introduce tu correo electrónico" />
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Restablecer
                                                contraseña</button>
                                            <a href="/sisvent/login.php" class="btn btn-lg btn-secondary">Regresar</a>
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
    <?php include "inc/close-connection.php"; ?>
</body>

</html>