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

// Inicializar variables para la búsqueda y el mensaje de error
$error_message = "";
$search_query = "";

// Verificar si hay una búsqueda
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim(mysqli_real_escape_string($open_connection, $_GET['search']));

    // Validar que la búsqueda sea por código, nombre, categoría o marca
    if (preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]*$/u", $search)) {
        $search_query = " AND (p.codigo LIKE '%$search%' OR p.nombre LIKE '%$search%' OR c.nombre LIKE '%$search%' OR m.nombre LIKE '%$search%')";
    } else {
        $error_message = "La búsqueda debe ser por código, nombre, categoría o marca.";
    }
}

// Preparar la consulta SQL
$query = "SELECT 
    LPAD(p.id_producto, 2, '0') AS id_producto, 
    p.imagen, 
    p.nombre, 
    p.codigo, 
    p.modelo, 
    p.color, 
    m.nombre AS marca, 
    c.nombre AS categoria,
    p.cantidad, 
    p.precio 
FROM 
    productos p 
LEFT JOIN 
    marcas m 
ON 
    p.marca_id = m.id_marca 
LEFT JOIN 
    categorias c 
ON 
    p.categoria_id = c.id_categoria
WHERE 
    p.estado = 1";

// Añadir la consulta de búsqueda si existe y es válida
if (!empty($search_query)) {
    $query .= $search_query;
}

$query .= " ORDER BY p.id_producto DESC;";

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
                    <h1 class="h3 mb-3">Productos</h1>
                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <div class="mt-3" id="success-delete-product"></div>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static"><i
                                                    class="align-middle" data-feather="more-horizontal"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="add-product.php">Añadir producto</a>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Lista de productos</h5>
                                    <h6 class="card-subtitle text-muted">A continuación se muestra el detalle completo
                                        de todos los productos.</h6>
                                </div>
                                <div class="card-header">
                                    <form id="search-form" method="GET" action="all-products.php">
                                        <label>Buscar productos por código, nombre, categoría o marca:
                                            <input type="search" class="form-control form-control-md mt-2" name="search"
                                                placeholder="Introduce búsqueda" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]*"
                                                title="Buscar por código, nombre, categoría o marca" required>
                                        </label>
                                        <button type="submit" class="btn btn-primary">Buscar Producto</button>
                                    </form>
                                </div>
                                <?php if (!empty($error_message)) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                                <?php } ?>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Imagen</th>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                            <th>Modelo</th>
                                            <th>Color</th>
                                            <th>Marca</th>
                                            <th>Categoría</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($results) > 0) {
                                            while ($row = mysqli_fetch_array($results)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['id_producto']; ?></td>
                                            <td><img src="<?php echo $row['imagen']; ?>" width="48" height="48"
                                                    class="rounded-circle mr-2" alt="<?php echo $row['nombre']; ?>">
                                            </td>
                                            <td><?php echo $row['nombre']; ?></td>
                                            <td><?php echo $row['codigo']; ?></td>
                                            <td><?php echo $row['modelo']; ?></td>
                                            <td><?php echo $row['color']; ?></td>
                                            <td><?php echo $row['marca']; ?></td>
                                            <td><?php echo $row['categoria']; ?></td>
                                            <td><?php echo $row['cantidad']; ?></td>
                                            <td>S/<?php echo $row['precio']; ?></td>
                                            <td class="table-action">
                                                <a class="link-edit"
                                                    href="edit-product.php?id=<?php echo $row['id_producto']; ?>"><i
                                                        class="align-middle mr-1" data-feather="edit-2"></i></a>
                                                <a class="link-delete" id="delete-product" href="#"
                                                    data-id="<?php echo $row['id_producto']; ?>"><i class="align-middle"
                                                        data-feather="trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="11"><i class="align-middle mr-1"
                                                    data-feather="alert-circle"></i> No hay datos suficientes para
                                                mostrar.</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </main>
        </div>
    </div>
    <?php require_once("inc/footer.php"); ?>