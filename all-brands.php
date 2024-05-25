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

?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>

        <div class="main">
            <?php require_once("inc/navbar.php"); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3">Marcas</h1>

                    <div class="row">

                        <div class="col-12 col-xl-12">
                            <div class="mt-3" id="success-delete-brand"></div>
                            <div class="card">

                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static"><i
                                                    class="align-middle" data-feather="more-horizontal"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="add-brand.php">Añadir marca</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Detalle de las Marcas</h5>
                                    <h6 class="card-subtitle text-muted">A continuación se muestra el detalle completo
                                        de todas las marcas.</h6>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Imagen</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										$query = "SELECT LPAD(id_marca, 2, '0') AS id_marca, imagen, nombre, descripcion FROM marcas WHERE estado = 1 ORDER BY nombre ASC";
										$results = mysqli_query($open_connection, $query);
										if (mysqli_num_rows($results)>0) {
											while ($row = mysqli_fetch_array($results)) { ?>
                                        <tr>
                                            <td><?php echo $row['id_marca'] ?></td>
                                            <td>
                                                <img src="<?php echo $row['imagen'] ?>" width="48" height="48"
                                                    class="rounded-circle mr-2" alt="<?php echo $row['nombre'] ?>">
                                            </td>
                                            <td><?php echo $row['nombre'] ?></td>
                                            <td><?php echo $row['descripcion'] ?></td>
                                            <td class="table-action">
                                                <a class="link-edit"
                                                    href="edit-brand.php?id=<?php echo $row['id_marca'] ?>"><i
                                                        class="align-middle mr-1" data-feather="edit-2"></i></a>
                                                <a class="link-delete" id="delete-brand" href="#"
                                                    data-id="<?php echo $row['id_marca'] ?>">
                                                    <i class="align-middle" data-feather="trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php }
										} else { ?>
                                        <td class="text-center" colspan="5"><i class="align-middle mr-1"
                                                data-feather="alert-circle"></i> No hay datos suficientes para mostrar.
                                        </td>
                                        <?php
										} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once("inc/footer.php"); ?>