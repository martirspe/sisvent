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

$id_categoria = $_GET['id'];

?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>

        <div class="main">
            <?php require_once("inc/navbar.php"); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3 text-center">Editar Categoría</h1>

                    <div class="row">
                        <?php
						$query = "SELECT LPAD(id_categoria, 2, '0') AS id_categoria, imagen, nombre, descripcion FROM categorias WHERE id_categoria = '$id_categoria'";
						$results = mysqli_query($open_connection, $query);
						while ($row = mysqli_fetch_array($results)) { ?>
                        <div class="col-12 col-md-10 offset-md-1 col-xl-8 offset-xl-2">
                            <div class="mt-3" id="success-update-category"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Actualizar Categoría</h5>
                                    <h6 class="card-subtitle text-muted">Edite los campos necesarios, no deje campos
                                        vacíos.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="update-category" action="inc/update-category.php" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group" hidden>
                                                <label class="form-label">Código</label>
                                                <input type="number" name="id" class="form-control"
                                                    value="<?php echo $row['id_categoria'] ?>">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="form-label">Nombre de la Categoría</label>
                                                <input type="text" name="nombre" class="form-control"
                                                    placeholder="Calzado" pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="La categoría debe contener solo letras y espacios"
                                                    value="<?php echo $row['nombre'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Descripción</label>
                                            <textarea class="form-control" name="descripcion"
                                                placeholder="Escribe una descripción corta para esta categoría."
                                                rows="3"><?php echo $row['descripcion'] ?></textarea>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Imagen</label>
                                                <p><img class="img-thumbnail rounded mr-2 mb-2"
                                                        src="<?php echo $row['imagen']?>"
                                                        alt="<?php echo $row['nombre']?>" width="100%" height="100%">
                                                </p>
                                            </div>
                                            <div class="form-group col-md-9 align-self-center">
                                                <label class="form-label w-100">Cambiar Imagen</label>
                                                <input type="file" name="imagen">
                                                <small class="form-text text-muted">Elija una nueva imagen para esta
                                                    categoría.</small>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
                                        <a href="/sisvent/all-categories.php" class="btn btn-secondary">Cancelar</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </main>
            <?php require_once("inc/footer.php"); ?>