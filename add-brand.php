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

                    <h1 class="h3 mb-3 text-center">Añadir Marca</h1>

                    <div class="row">
                        <div class="col-12 col-md-10 offset-md-1 col-xl-8 offset-xl-2">
                            <div class="mt-3" id="success-add-brand"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Nueva Marca</h5>
                                    <h6 class="card-subtitle text-muted">Ingrese datos en todos los campos a
                                        continuación.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="add-brand" action="inc/add-brand.php" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="form-label">Nombre de la Marca</label>
                                            <input type="text" name="nombre" class="form-control" placeholder="Ingrese una marca"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                title="La marca debe contener solo letras y espacios" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Descripción</label>
                                            <textarea class="form-control" name="descripcion"
                                                placeholder="Ingrese una descripción para esta marca" rows="3"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                title="La descripción debe contener solo letras y espacios"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label w-100">Imagen</label>
                                            <input type="file" name="imagen">
                                            <small class="form-text text-muted">Elija una nueva imagen para esta
                                                marca.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Añadir Marca</button>
                                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once("inc/footer.php"); ?>