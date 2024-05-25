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

                    <h1 class="h3 mb-3 text-center">Añadir Producto</h1>

                    <div class="row">
                        <?php
                        $query = "SELECT id_categoria FROM categorias";
                        $results = mysqli_query($open_connection, $query);
                        if (mysqli_num_rows($results)>0) { ?>
                        <div class="col-12 col-md-10 offset-md-1 col-xl-6 offset-xl-3">
                            <div class="mt-3" id="success-add-product"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Nuevo Producto</h5>
                                    <h6 class="card-subtitle text-muted">Ingrese datos en todos los campos a
                                        continuación.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="add-product" action="inc/add-product.php" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" name="nombre" class="form-control"
                                                    placeholder="Ingrese un nombre para este producto"
                                                    pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El nombre debe contener solo letras y espacios" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Código</label>
                                                <input type="text" name="codigo" class="form-control" placeholder="100"
                                                    pattern="[0-9]+"
                                                    title="El código de producto debe contener solo números" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Modelo</label>
                                                <input type="text" name="modelo" class="form-control" placeholder="100"
                                                    required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Color</label>
                                                <input type="text" name="color" class="form-control" placeholder="Negro"
                                                    pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El color debe contener solo letras y espacios" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Marca</label>
                                                <select name="marca" class="form-control first-item" required>
                                                    <option value="">Elije</option>
                                                    <?php
													$query = "SELECT id_marca, nombre FROM marcas";
													$results = mysqli_query($open_connection,$query);
													while ($row_u = mysqli_fetch_array($results)) { ?>
                                                    <option value="<?php echo $row_u['id_marca'] ?>">
                                                        <?php echo $row_u['nombre'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label class="form-label">Categoría</label>
                                                <select name="categoria" class="form-control first-item" required>
                                                    <option value="">Elije</option>
                                                    <?php
													$query = "SELECT id_categoria, nombre FROM categorias";
													$results = mysqli_query($open_connection,$query);
													while ($row_u = mysqli_fetch_array($results)) { ?>
                                                    <option value="<?php echo $row_u['id_categoria'] ?>">
                                                        <?php echo $row_u['nombre'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-label">Cantidad</label>
                                                <input type="number" name="cantidad" class="form-control"
                                                    placeholder="10" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-label">Precio</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">S/.</span>
                                                    </div>
                                                    <input type="decimal" name="precio" class="form-control"
                                                        placeholder="89.90" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Descripción</label>
                                            <textarea class="form-control" name="descripcion"
                                                placeholder="Escribe una descripción corta para este producto" rows="3"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                title="La descripción debe contener solo letras y espacios"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label w-100">Imagen</label>
                                            <input type="file" name="imagen">
                                            <small class="form-text text-muted">Elija una nueva imagen para este
                                                producto.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Añadir Producto</button>
                                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php 
						} else { ?>
                        <div class="col-md-8 offset-md-2">
                            <div class="alert alert-primary alert-outline-coloured alert-dismissible" role="alert">
                                <div class="alert-icon">
                                    <i data-feather="alert-circle"></i>
                                </div>
                                <div class="alert-message">
                                    Es necesario agregar categorías para luego añadir productos.
                                </div>
                            </div>
                            <?php 
                        }?>
                        </div>
                    </div>
            </main>
            <?php require_once("inc/footer.php"); ?>