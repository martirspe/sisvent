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

$id = $_GET['id'];

?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>

        <div class="main">
            <?php require_once("inc/navbar.php"); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3 text-center">Editar Producto</h1>

                    <div class="row">
                        <?php
                        $query = "SELECT 
                                    p.id_producto, 
                                    p.nombre, 
                                    p.codigo, 
                                    p.modelo, 
                                    p.color, 
                                    p.precio, 
                                    p.descripcion, 
                                    p.imagen, 
                                    p.categoria_id, 
                                    p.marca_id, 
                                    m.nombre AS marca, 
                                    c.nombre AS categoria 
                                  FROM 
                                    productos p
                                  LEFT JOIN 
                                    marcas m ON p.marca_id = m.id_marca
                                  LEFT JOIN 
                                    categorias c ON p.categoria_id = c.id_categoria
                                  WHERE 
                                    p.id_producto='$id'";
                        $results = mysqli_query($open_connection, $query);
                        if ($row = mysqli_fetch_array($results)) { ?>
                        <div class="col-12 col-md-6 offset-md-1 col-xl-6 offset-xl-3">
                            <div class="mt-3" id="success-update-product"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Actualizar Producto</h5>
                                    <h6 class="card-subtitle text-muted">Edite los campos necesarios, no deje campos
                                        vacíos.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="update-product" action="inc/update-product.php" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label class="form-label">Nombre del Producto</label>
                                                <input type="text" name="nombre" class="form-control"
                                                    value="<?php echo $row['nombre']; ?>"
                                                    placeholder="Calzado de Puro Cuero" pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El nombre debe contener solo letras y espacios" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Código</label>
                                                <input type="text" name="codigo" class="form-control"
                                                    value="<?php echo $row['codigo']; ?>" placeholder="1001" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Modelo</label>
                                                <input type="text" name="modelo" class="form-control"
                                                    value="<?php echo $row['modelo']; ?>" placeholder="508" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Color</label>
                                                <input type="text" name="color" class="form-control"
                                                    value="<?php echo $row['color']; ?>" placeholder="Negro"
                                                    pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                    title="El color debe contener solo letras y espacios" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Marca</label>
                                                <select name="marca" class="form-control first-item" required>
                                                    <option value="">Elije</option>
                                                    <?php
                                                    $marca_query = "SELECT id_marca, nombre FROM marcas";
                                                    $marca_results = mysqli_query($open_connection, $marca_query);
                                                    while ($marca_row = mysqli_fetch_array($marca_results)) { ?>
                                                    <option value="<?php echo $marca_row['id_marca']; ?>"
                                                        <?php echo ($marca_row['id_marca'] == $row['marca_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $marca_row['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Categoría</label>
                                                <select name="categoria" class="form-control first-item" required>
                                                    <option value="">Elije</option>
                                                    <?php
                                                    $categoria_query = "SELECT id_categoria, nombre FROM categorias";
                                                    $categoria_results = mysqli_query($open_connection, $categoria_query);
                                                    while ($categoria_row = mysqli_fetch_array($categoria_results)) { ?>
                                                    <option value="<?php echo $categoria_row['id_categoria']; ?>"
                                                        <?php echo ($categoria_row['id_categoria'] == $row['categoria_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $categoria_row['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Precio</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">S/.</span>
                                                    </div>
                                                    <input type="number" name="precio" class="form-control"
                                                        placeholder="89.90" value="<?php echo $row['precio']; ?>"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Descripción</label>
                                            <textarea class="form-control" name="descripcion"
                                                placeholder="Escribe una descripción corta para este producto." rows="3"
                                                pattern="[A-Za-zÁÉÍÓÚáéíóú\s]+"
                                                title="La descripción debe contener solo letras y espacios"
                                                required><?php echo $row['descripcion']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label w-100">Imagen</label>
                                            <img src="<?php echo $row['imagen']; ?>" alt="Imagen del Producto"
                                                class="img-thumbnail" width="200">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label w-100">Cambiar Imagen</label>
                                            <input type="file" name="imagen">
                                            <small class="form-text text-muted">Elija la nueva imagen del producto si
                                                desea cambiarla.</small>
                                        </div>
                                        <input type="hidden" name="id_producto"
                                            value="<?php echo $row['id_producto']; ?>">
                                        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                                        <a href="productos.php" class="btn btn-secondary">Cancelar</a>
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
                                    No se encontró el producto con el ID especificado.
                                </div>
                            </div>
                        </div>
                        <?php 
                        }?>
                    </div>
                </div>
            </main>
            <?php require_once("inc/footer.php"); ?>