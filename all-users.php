<?php

// Incluir archivo de conexión
include "inc/open-connection.php";

// Iniciar sesión
session_start();

// Verificar si el usuario está activo
if (empty($_SESSION['active'])) {
    header("location: login.php");
    exit();
}

// Obtener el ID del rol del usuario logueado
$id_usuario = $_SESSION['id_usuario'];
$query_role = "SELECT rol_id FROM usuarios WHERE id_usuario = ?";
$stmt_role = mysqli_prepare($open_connection, $query_role);
mysqli_stmt_bind_param($stmt_role, "i", $id_usuario);
mysqli_stmt_execute($stmt_role);
mysqli_stmt_store_result($stmt_role);
mysqli_stmt_bind_result($stmt_role, $user_role);
mysqli_stmt_fetch($stmt_role);

// Inicializar variables para el mensaje de error y la consulta de búsqueda
$error_message = "";
$search_query = "";

// Verificar si hay una búsqueda
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim(mysqli_real_escape_string($open_connection, $_GET['search']));
    
    // Validar que la búsqueda sea por nombre, apellido o número de documento (8 dígitos)
    if (!preg_match("/^[\p{L}\s'-]+$/u", $search) && !preg_match("/^\d{8}$/", $search)) {
        $error_message = "La búsqueda debe ser por nombre, apellido o número de documento (8 dígitos).";
    } else {
        $search_query = " AND (u.nombres LIKE '%$search%' OR u.apellidos LIKE '%$search%' OR u.dni LIKE '%$search%')";
    }
}

// Preparar la consulta SQL
$query = "SELECT
    LPAD(u.id_usuario, 2, '0') AS id_usuario,
    u.imagen,
    u.dni,
    u.nombres,
    u.apellidos,
    u.movil,
    u.email,
    u.direccion,
    u.rol_id,
    r.nombre AS nombre_rol
FROM
    usuarios u
INNER JOIN roles r ON
    u.rol_id = r.id_rol
WHERE
    u.estado = 1";

// Si el usuario logueado es cliente, ocultar los administradores
if ($user_role == 2) { // ID del rol de cliente
    $query .= " AND u.rol_id != 1"; // ID del rol de administrador
}

// Añadir la consulta de búsqueda si existe y es válida
if (!empty($search_query)) {
    $query .= $search_query;
}

$query .= " ORDER BY u.id_usuario DESC;";

$results = mysqli_query($open_connection, $query);

?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>
        <div class="main">
            <?php require_once("inc/navbar.php"); ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3">Usuarios</h1>
                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <div class="mt-3" id="success-delete-user"></div>
                            <?php if (!empty($error_message)) { ?>
                            <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                                <div class="alert-icon">
                                    <i class="far fa-fw fa-bell"></i>
                                </div>
                                <div class="alert-message">
                                    <?php echo $error_message; ?>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <?php } ?>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static"><i
                                                    class="align-middle" data-feather="more-horizontal"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="add-user.php">Añadir usuario</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Lista de usuarios</h5>
                                    <h6 class="card-subtitle text-muted">A continuación se muestra el detalle completo
                                        de todos los usuarios.</h6>
                                </div>
                                <div class="card-header">
                                    <form id="search-form" method="GET" action="all-users.php">
                                        <label>Buscar por nombre, apellido o número de documento:
                                            <input type="search" class="form-control form-control-md mt-2" name="search"
                                                placeholder="Introduce búsqueda" required>
                                        </label>
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                    </form>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Imagen</th>
                                            <th>DNI</th>
                                            <th>Nombre completo</th>
                                            <th>Correo</th>
                                            <th>Móvil</th>
                                            <th>Dirección</th>
                                            <th>Rol de usuario</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($results) > 0) {
                                            while ($row = mysqli_fetch_array($results)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['id_usuario']; ?></td>
                                            <td><img src="<?php echo $row['imagen']; ?>" width="48" height="48"
                                                    class="rounded-circle mr-2" alt="<?php echo $row['nombres']; ?>">
                                            </td>
                                            <td><?php echo $row['dni']; ?></td>
                                            <td><?php echo $row['nombres']; ?> <?php echo $row['apellidos']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['movil']; ?></td>
                                            <td><?php echo $row['direccion']; ?></td>
                                            <td><?php echo $row['nombre_rol']; ?></td>
                                            <td class="table-action">
                                                <a class="link-edit"
                                                    href="edit-user.php?id=<?php echo $row['id_usuario']; ?>">
                                                    <i class="align-middle mr-1" data-feather="edit-2"></i>
                                                </a>
                                                <?php if ($row['rol_id'] != 1) { ?>
                                                <a class="link-delete" id="delete-user" href="#"
                                                    data-id="<?php echo $row['id_usuario']; ?>">
                                                    <i class="align-middle" data-feather="trash"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } else { ?>
                                        <td class="text-center" colspan="9">
                                            <i class="align-middle mr-1" data-feather="alert-circle"></i> No hay datos
                                            suficientes para mostrar.
                                        </td>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php require_once("inc/footer.php"); ?>