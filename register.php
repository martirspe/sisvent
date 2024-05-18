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
                            <h1 class="h2">Registrarse</h1>
                            <p class="lead">Crea tu cuenta y organízate fácilmente.</p>
                        </div>

                        <div class="mt-3" id="success-register"></div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-4">
                                    <form id="register" action="inc/register.php" method="POST">
                                        <div class="form-group">
                                            <label>DNI</label>
                                            <input class="form-control form-control-lg" type="text" name="dni"
                                                maxlength="8" placeholder="88888888" pattern="[0-9]{8}"
                                                title="El DNI debe contener solo números y tener una longitud de 8 caracteres"
                                                required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Nombre</label>
                                                <input class="form-control form-control-lg" type="text" name="nombres"
                                                    placeholder="Jon" pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El nombre debe contener solo letras y espacios" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Apellidos</label>
                                                <input class="form-control form-control-lg" type="text" name="apellidos"
                                                    placeholder="Doe" pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El apellido debe contener solo letras y espacios" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Móvil</label>
                                                <input class="form-control form-control-lg" type="text" name="movil"
                                                    maxlength="9" placeholder="999999999" pattern="[0-9]{9}"
                                                    title="El número de móvil debe contener solo números y tener una longitud de 9 caracteres"
                                                    required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Email</label>
                                                <input class="form-control form-control-lg" type="email" name="correo"
                                                    placeholder="jdoe@example.com"
                                                    pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <input class="form-control form-control-lg" type="text" name="direccion"
                                                placeholder="Av. Marginal #145 - ATE, Lima" required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Contraseña</label>
                                                <input class="form-control form-control-lg" type="password"
                                                    name="password" minlength="8" placeholder="Crea tu contraseña"
                                                    required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Confirmar contraseña</label>
                                                <input class="form-control form-control-lg" type="password"
                                                    name="password2" placeholder="Repite tu contraseña" />
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Registrarme</button>
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