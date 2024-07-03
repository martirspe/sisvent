<?php
include "inc/open-connection.php";
session_start();

// Verificar si el usuario está activo
if (empty($_SESSION['active'])) {
    header("location: login.php");
    exit();
}

// Obtener parámetros de ordenación
$order = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'asc' : 'desc';
$new_order = $order == 'asc' ? 'desc' : 'asc';

// Obtener parámetros de filtro de fechas
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Configuración de paginación
$limit = 15; // Número de ventas por página
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Contar el número total de ventas
$count_query = "SELECT COUNT(*) as total FROM ventas v JOIN usuarios u ON v.usuario_id = u.id_usuario";
if (!empty($start_date) && !empty($end_date)) {
    $count_query .= " WHERE v.fecha_venta BETWEEN '$start_date' AND '$end_date'";
}
$count_result = mysqli_query($open_connection, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_sales = $count_row['total'];
$total_pages = ceil($total_sales / $limit);

include "inc/header.php"; 
?>

<body>
    <div class="wrapper">
        <?php include "inc/sidebar.php"; ?>
        <div class="main">
            <?php include "inc/navbar.php"; ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3 text-center">Todas las Ventas</h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Listado de Ventas</h5>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="all-orders.php">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="start_date">Fecha Inicio</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="end_date">Fecha Fin</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                                            </div>
                                            <div class="form-group col-md-3 align-self-end">
                                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                            </div>
                                        </div>
                                    </form>

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID Venta</th>
                                                <th>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Fecha Venta
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="all-orders.php?order=desc">Fecha Reciente</a>
                                                            <a class="dropdown-item" href="all-orders.php?order=asc">Fecha Antigua</a>
                                                        </div>
                                                    </div>
                                                </th>
                                                <th>Total</th>
                                                <th>Cliente</th>
                                                <th>DNI</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT v.id_venta, v.fecha_venta, v.total, u.nombres AS cliente_nombres, u.apellidos AS cliente_apellidos, u.dni AS cliente_dni FROM ventas v 
                                                      JOIN usuarios u ON v.usuario_id = u.id_usuario ";
                                            
                                            // Agregar filtro de fechas si se han proporcionado
                                            if (!empty($start_date) && !empty($end_date)) {
                                                $query .= "WHERE v.fecha_venta BETWEEN '$start_date' AND '$end_date' ";
                                            }
                                            
                                            $query .= "ORDER BY v.fecha_venta $order LIMIT $limit OFFSET $offset";
                                            $results = mysqli_query($open_connection, $query);
                                            
                                            if (mysqli_num_rows($results) > 0) {
                                                while ($row = mysqli_fetch_assoc($results)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['id_venta'] . "</td>";
                                                    echo "<td>" . $row['fecha_venta'] . "</td>";
                                                    echo "<td>" . $row['total'] . "</td>";
                                                    echo "<td>" . $row['cliente_nombres'] . " " . $row['cliente_apellidos'] . "</td>";
                                                    echo "<td>" . $row['cliente_dni'] . "</td>";
                                                    echo '<td>';
                                                    echo '<a href="edit-order.php?id=' . $row['id_venta'] . '" class="btn btn-primary btn-sm">Editar</a>';
                                                    echo ' <button class="btn btn-danger btn-sm" onclick="anularVenta(' . $row['id_venta'] . ')">Anular</button>';
                                                    echo '</td>';
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center'>No se encontraron ventas</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <?php if($page > 1): ?>
                                                <li class="page-item"><a class="page-link" href="all-orders.php?page=<?php echo $page - 1; ?>&order=<?php echo $order; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>">Anterior</a></li>
                                            <?php endif; ?>
                                            
                                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                                <li class="page-item <?php if($i == $page) echo 'active'; ?>"><a class="page-link" href="all-orders.php?page=<?php echo $i; ?>&order=<?php echo $order; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>"><?php echo $i; ?></a></li>
                                            <?php endfor; ?>
                                            
                                            <?php if($page < $total_pages): ?>
                                                <li class="page-item"><a class="page-link" href="all-orders.php?page=<?php echo $page + 1; ?>&order=<?php echo $order; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>">Siguiente</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include "inc/footer.php"; ?>
        </div>
    </div>

    <script>
        function anularVenta(idVenta) {
            if (confirm('¿Estás seguro de que deseas anular esta venta?')) {
                window.location.href = 'inc/anular-order.php?id=' + idVenta;
            }
        }
    </script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include "inc/close-connection.php";
?>
