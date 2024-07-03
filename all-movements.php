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

// Verificar si hay una búsqueda
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim(mysqli_real_escape_string($open_connection, $_GET['search']));
    // Modificar la consulta de búsqueda para buscar solo por código de producto
    $search_query = " AND p.codigo LIKE '%$search%'";
}

// Preparar la consulta SQL para obtener los movimientos de stock
$query = "
SELECT 
    LPAD(m.id_movimiento, 2, '0') AS id_movimiento,
    p.nombre AS producto_nombre, 
    p.codigo, 
    p.modelo, 
    p.color, 
    mca.nombre AS marca, 
    cat.nombre AS categoria, 
    m.tipo_movimiento, 
    m.cantidad, 
    m.fecha_movimiento 
FROM 
    movimientos_stock m
JOIN 
    productos p 
ON 
    m.id_producto = p.id_producto 
LEFT JOIN 
    marcas mca 
ON 
    p.marca_id = mca.id_marca 
LEFT JOIN 
    categorias cat 
ON 
    p.categoria_id = cat.id_categoria 
WHERE 
    1=1 $search_query 
ORDER BY 
    m.fecha_movimiento DESC
";

// Ejecutar la consulta
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

                    <h1 class="h3 mb-3">Movimientos de Stock</h1>

                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <div class="mt-3" id="success-delete-product"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Lista de Movimientos</h5>
                                    <h6 class="card-subtitle text-muted">A continuación se muestra el detalle completo
                                        de todos los movimientos de stock.</h6>
                                </div>
                                <div class="card-header">
                                    <form id="search-form" method="GET" action="all-movements.php">
                                        <label>Buscar movimientos por código de producto:
                                            <input type="search" class="form-control form-control-md mt-2" name="search"
                                                placeholder="Introduce búsqueda" pattern="[0-9]+"
                                                title="El código del producto debe contener solo números">
                                        </label>
                                        <button type="submit" class="btn btn-primary">Buscar Movimientos</button>
                                    </form>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Producto</th>
                                            <th>Código</th>
                                            <th>Modelo</th>
                                            <th>Color</th>
                                            <th>Marca</th>
                                            <th>Categoría</th>
                                            <th>Tipo de Movimiento</th>
                                            <th>Cantidad</th>
                                            <th>Fecha y Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($results) > 0) {
                                            while ($row = mysqli_fetch_array($results)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['id_movimiento']; ?></td>
                                            <td><?php echo $row['producto_nombre']; ?></td>
                                            <td><?php echo $row['codigo']; ?></td>
                                            <td><?php echo $row['modelo']; ?></td>
                                            <td><?php echo $row['color']; ?></td>
                                            <td><?php echo $row['marca']; ?></td>
                                            <td><?php echo $row['categoria']; ?></td>
                                            <td><?php echo ucfirst($row['tipo_movimiento']); ?></td>
                                            <td><?php echo $row['cantidad']; ?></td>
                                            <td><?php echo $row['fecha_movimiento']; ?></td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="10"><i class="align-middle mr-1"
                                                    data-feather="alert-circle"></i> No hay movimientos de stock
                                                registrados.</td>
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