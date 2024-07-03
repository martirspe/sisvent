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
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim(mysqli_real_escape_string($open_connection, $_GET['search']));
    $search_query = " AND (p.codigo LIKE '%$search%' OR p.nombre LIKE '%$search%' OR c.nombre LIKE '%$search%' OR m.nombre LIKE '%$search%')";
}

// Preparar la consulta SQL
$query = "
SELECT 
    p.id_producto,
    p.imagen,
    p.nombre,
    p.codigo,
    p.modelo,
    p.color,
    m.nombre AS marca,
    c.nombre AS categoria,
    p.cantidad,
    (
        SELECT 
            MAX(fecha_movimiento)
        FROM 
            movimientos_stock
        WHERE 
            id_producto = p.id_producto
    ) AS ultimo_movimiento
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

// Añadir la consulta de búsqueda si existe
if (!empty($search_query)) {
    $query .= $search_query;
}

$query .= " GROUP BY p.id_producto ORDER BY p.id_producto DESC;";
$results = mysqli_query($open_connection, $query);

// Procesar formulario para registrar movimiento de stock
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['codigo']) && isset($_GET['t_movimiento']) && isset($_GET['cantidad'])) {
        $codigo = mysqli_real_escape_string($open_connection, $_GET['codigo']);
        $tipo_movimiento = mysqli_real_escape_string($open_connection, $_GET['t_movimiento']);
        $cantidad = (int) $_GET['cantidad'];

        // Verificar si el producto con el código especificado existe
        $check_query = "SELECT id_producto, cantidad FROM productos WHERE codigo = '$codigo'";
        $check_result = mysqli_query($open_connection, $check_query);

        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $producto = mysqli_fetch_assoc($check_result);
            $id_producto = $producto['id_producto'];
            $stock_actual = $producto['cantidad'];

            // Validar si el tipo de movimiento es salida y la cantidad es mayor que el stock actual
            if ($tipo_movimiento == 'Salida' && $cantidad > $stock_actual) {
                $error_message = '
                    <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                        <div class="alert-icon">
                            <i class="far fa-fw fa-bell"></i>
                        </div>
                        <div class="alert-message">
                            La cantidad a retirar es mayor que el stock actual.
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                ';
            } else {
                // Llamar al procedimiento almacenado para registrar el movimiento de stock
                $query = "CALL registrar_movimiento_stock($id_producto, '$tipo_movimiento', $cantidad)";
                $result = mysqli_query($open_connection, $query);

                if ($result) {
                    // Éxito en registrar el movimiento
                    $error_message = '
                        <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                            <div class="alert-icon">
                                <i class="far fa-fw fa-bell"></i>
                            </div>
                            <div class="alert-message">
                                Movimiento de stock registrado correctamente. La página se actualizará en breve.
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    ';
                    header("Location: stock-management.php?registrado=1");
                    exit();
                } else {
                    // Error al registrar el movimiento
                    $error_message = '
                        <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                            <div class="alert-icon">
                                <i class="far fa-fw fa-bell"></i>
                            </div>
                            <div class="alert-message">
                                Error al registrar el movimiento de stock.
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    ';
                }
            }
        } else {
            // Producto no encontrado
            $error_message = '
                <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        El producto especificado no existe.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        }
    } else {
        $error_message = '';
    }
}

// Consulta para obtener las alertas de bajo stock, mostrando solo el último movimiento de cada producto
$query_alertas = "
SELECT 
    p.codigo AS codigo_producto, 
    p.nombre AS nombre_producto, 
    a.mensaje, 
    a.fecha_alerta
FROM 
    alertas_stock a
INNER JOIN 
    (SELECT id_producto, MAX(fecha_alerta) AS ultima_alerta
     FROM alertas_stock
     GROUP BY id_producto) ultimas_alertas
ON 
    a.id_producto = ultimas_alertas.id_producto
    AND a.fecha_alerta = ultimas_alertas.ultima_alerta
INNER JOIN 
    productos p 
ON 
    a.id_producto = p.id_producto
ORDER BY 
    a.fecha_alerta DESC";

$result_alertas = mysqli_query($open_connection, $query_alertas);
?>

<?php include "inc/header.php"; ?>

<body>
    <div class="wrapper">
        <?php require_once("inc/sidebar.php"); ?>

        <div class="main">
            <?php require_once("inc/navbar.php"); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3">Gestión de Stock de Productos</h1>

                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <?php echo $error_message;?>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Registrar movimiento de stock</h5>
                                    <form id="search-form" method="GET" action="stock-management.php">
                                        <label>
                                            <input type="search" class="form-control form-control-md mt-2" name="codigo"
                                                placeholder="Código del producto" required>
                                        </label>
                                        <label>
                                            <select name="t_movimiento" class="form-control first-item" required>
                                                <option value="">Tipo de Movimiento</option>
                                                <option value="Entrada">Entrada</option>
                                                <option value="Salida">Salida</option>
                                            </select>
                                        </label>
                                        <label>
                                            <input type="search" class="form-control form-control-md mt-2"
                                                name="cantidad" placeholder="Cantidad" required>
                                        </label>
                                        <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
                                        <a href="/sisvent/all-movements.php" class="btn btn-secondary">Ver
                                            Movimientos</a>
                                    </form>

                                </div>
                                <div class="card-header">
                                    <h5 class="card-title">Alertas de bajo stock</h5>
                                    <h6 class="card-subtitle text-muted">A continuación se muestra las alertas de
                                        productos con bajo stock.</h6>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Mensaje</th>
                                            <th>Fecha y hora de alerta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($result_alertas) > 0) {
                                            while ($row_alerta = mysqli_fetch_array($result_alertas)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row_alerta['codigo_producto']; ?></td>
                                            <td><?php echo $row_alerta['nombre_producto']; ?></td>
                                            <td><?php echo $row_alerta['mensaje']; ?></td>
                                            <td><?php echo $row_alerta['fecha_alerta']; ?></td>
                                        </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No hay alertas de bajo stock.</td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="card-header">
                                    <h5 class="card-title">Lista de productos</h5>
                                    <h6 class="card-subtitle text-muted">A continuación se muestra el detalle completo
                                        de todos los productos.</h6>
                                </div>
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
    <script>
    // Detectar cambios en la URL para recargar la página automáticamente
    if (window.location.href.indexOf('registrado=1') > -1) {
        setTimeout(() => {
            window.location.href = window.location.href.split('?')[0];
        }, 2000);
    }
    </script>
    <?php require_once("inc/footer.php"); ?>