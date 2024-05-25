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

// Obtener el ID del usuario de la consulta GET
$id_usuario = isset($_GET['id']) ? $_GET['id'] : '';

?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>

        <div class="main">
            <?php require_once("inc/navbar.php"); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3 text-center">Editar Usuario</h1>

                    <div class="row">
                        <?php
                        $query = "SELECT id_usuario, imagen, dni, nombres, apellidos, movil, email, direccion, rol_id, estado FROM usuarios WHERE id_usuario = '$id_usuario'";
                        $results = mysqli_query($open_connection, $query);
						while ($row = mysqli_fetch_array($results)) { ?>
                        <div class="col-12 col-md-10 offset-md-1 col-xl-6 offset-xl-3">
                            <div class="mt-3" id="success-update-user"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Actualizar Usuario</h5>
                                    <h6 class="card-subtitle text-muted">Edite los campos necesarios, no deje campos
                                        vacíos.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="update-user" action="inc/update-user.php" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-2" hidden>
                                                <label class="form-label">ID</label>
                                                <input type="text" name="id" class="form-control" placeholder="0001"
                                                    value="<?php echo $row['id_usuario'] ?>">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="form-label">DNI</label>
                                                <input type="text" name="dni" maxlength="8" class="form-control"
                                                    placeholder="12345678" value="<?php echo $row['dni'] ?>"
                                                    pattern="[0-9]{8}"
                                                    title="El DNI debe contener solo números y tener una longitud de 8 caracteres"
                                                    required>
                                            </div>

                                            <div class="form-group col-md-5">
                                                <label class="form-label">Nombres</label>
                                                <input type="text" name="nombres" class="form-control" placeholder="Jon"
                                                    value="<?php echo $row['nombres'] ?>"
                                                    pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El nombre debe contener solo letras y espacios" required>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label class="form-label">Apellidos</label>
                                                <input type="text" name="apellidos" class="form-control"
                                                    placeholder="Doe" value="<?php echo $row['apellidos'] ?>"
                                                    pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El apellido debe contener solo letras y espacios" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6" hidden>
                                                <label class="form-label">Rol de usuario</label>
                                                <select name="tipo" class="form-control first-item">
                                                    <option value="">Elije un rol de usuario</option>
                                                    <?php
                                                    $marca_query = "SELECT id_rol, nombre FROM roles";
                                                    $marca_results = mysqli_query($open_connection, $marca_query);
                                                    while ($roles_row = mysqli_fetch_array($marca_results)) { ?>
                                                    <option value="<?php echo $roles_row['id_rol']; ?>"
                                                        <?php echo ($roles_row['id_rol'] == $row['rol_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $roles_row['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Rol de usuario</label>
                                                <select name="tipo" class="form-control first-item"
                                                    <?php echo ($usuario_cliente) ? 'disabled' : '';?>>
                                                    <option value="">Elije un rol de usuario</option>
                                                    <?php
                                                    $marca_query = "SELECT id_rol, nombre FROM roles";
                                                    $marca_results = mysqli_query($open_connection, $marca_query);
                                                    while ($roles_row = mysqli_fetch_array($marca_results)) { ?>
                                                    <option value="<?php echo $roles_row['id_rol']; ?>"
                                                        <?php echo ($roles_row['id_rol'] == $row['rol_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $roles_row['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Móvil</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">+51</span>
                                                    </div>
                                                    <input type="text" name="movil" maxlength="9" class="form-control"
                                                        placeholder="999999999" value="<?php echo $row['movil'] ?>"
                                                        pattern="[0-9]{9}"
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
                                                        value="<?php echo $row['email'] ?>"
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
                                                        placeholder="Ingrese una contraseña">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" name="direccion" class="form-control"
                                                placeholder="Ingrese una dirección"
                                                value="<?php echo $row['direccion'] ?>" required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Imagen</label>
                                                <p><img class="img-thumbnail rounded mr-2 mb-2"
                                                        src="<?php echo $row['imagen'] ?>"
                                                        alt="<?php echo $row['nombres'] ?>" width="100%" height="100%">
                                                </p>
                                            </div>
                                            <div class="form-group col-md-9 align-self-center">
                                                <label class="form-label w-100">Foto de perfil</label>
                                                <input type="file" name="imagen">
                                                <small class="form-text text-muted">Elija una nueva imagen para este
                                                    usuario.</small>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                                        <a href="/sisvent/all-users.php" class="btn btn-secondary">Cancelar</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php require_once("inc/footer.php"); ?>
</body>

</html>