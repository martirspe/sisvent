<?php

// Incluir archivo de conexión
include "inc/open-connection.php";

// Iniciar sesión
session_start();

// Verificar si el usuario está activo
if (empty($_SESSION['active'])) {
    header("location: login.php");
    exit(); // Asegura que el script se detenga después de redirigir
}

// Verificar si el usuario ha iniciado sesión y si es un cliente
$usuario_cliente = false; // Suponiendo que inicialmente no es un cliente
if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2) {
    // Si el rol del usuario es 2 (cliente), entonces es un cliente
    $usuario_cliente = true;
}

// Obtener roles de la base de datos
$query = "SELECT id_rol, nombre FROM roles";
$result = mysqli_query($open_connection, $query);

// Verificar si se obtuvieron resultados de la consulta
if ($result && mysqli_num_rows($result) > 0) {
    // Crear opciones para el combo de rol de usuario
    $options = '';
    while ($row = mysqli_fetch_assoc($result)) {
        // Si el usuario es un cliente y el rol actual es Administrador, no agregarlo a las opciones
        if ($usuario_cliente && $row['nombre'] === 'Administrador') {
            continue;
        }
        $options .= '<option value="' . $row['id_rol'] . '">' . $row['nombre'] . '</option>';
    }
} else {
    // Si no se obtuvieron resultados, mostrar un mensaje de error o manejar la situación de otra manera
    $options = '<option value="">No hay roles disponibles</option>';
}

?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>

        <div class="main">
            <?php require_once("inc/navbar.php"); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3 text-center">Añadir Usuario</h1>

                    <div class="row">
                        <div class="col-12 col-md-10 offset-md-1 col-xl-6 offset-xl-3">
                            <div class="mt-3" id="success-add-user"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Nuevo Usuario</h5>
                                    <h6 class="card-subtitle text-muted">Ingrese datos en todos los campos a
                                        continuación.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="add-user" action="inc/add-user.php" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <label class="form-label">DNI</label>
                                                <input type="text" name="dni" maxlength="8" class="form-control"
                                                    placeholder="12345678" pattern="[0-9]{8}"
                                                    title="El DNI debe contener solo números y tener una longitud de 8 caracteres"
                                                    required>
                                            </div>

                                            <div class="form-group col-md-5">
                                                <label class="form-label">Nombres</label>
                                                <input type="text" name="nombres" class="form-control" placeholder="Jon"
                                                    pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El nombre debe contener solo letras y espacios" required>
                                            </div>

                                            <div class="form-group col-md-5">
                                                <label class="form-label">Apellidos</label>
                                                <input type="text" name="apellidos" class="form-control"
                                                    placeholder="Doe" pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El apellido debe contener solo letras y espacios" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Rol de usuario</label>
                                                <select name="tipo" class="form-control first-item" required>
                                                    <option value="">Elije un rol de usuario</option>
                                                    <?php echo $options; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Móvil</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">+51</span>
                                                    </div>
                                                    <input type="text" name="movil" maxlength="9" class="form-control"
                                                        placeholder="999999999" pattern="[0-9]{9}"
                                                        title="El número de móvil debe contener solo números y tener una longitud de 9 caracteres"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Correo electrónico</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">@</span>
                                                    </div>
                                                    <input type="email" name="correo" class="form-control"
                                                        placeholder="jdoe@example.com"
                                                        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Contraseña</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="align-middle"
                                                                data-feather="lock"></i></span>
                                                    </div>
                                                    <input type="password" name="password" class="form-control"
                                                        placeholder="Ingrese una contraseña" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" name="direccion" class="form-control"
                                                placeholder="Ingrese una dirección" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label w-100">Foto de perfil</label>
                                            <input type="file" name="imagen">
                                            <small class="form-text text-muted">Elija una nueva imagen para este
                                                usuario.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Añadir Usuario</button>
                                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php require_once("inc/footer.php"); ?>
</body>

</html>