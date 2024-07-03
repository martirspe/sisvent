<?php
include "inc/open-connection.php";
session_start();

// Verificar si el usuario está activo
if (empty($_SESSION['active'])) {
    header("location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['message'] = "Venta no encontrada.";
    $_SESSION['message_type'] = "danger";
    header("Location: all-orders.php");
    exit();
}

$idVenta = $_GET['id'];

// Obtener los datos de la venta
$query = "SELECT v.*, u.dni, u.nombres, u.apellidos, u.direccion FROM ventas v 
          JOIN usuarios u ON v.usuario_id = u.id_usuario 
          WHERE v.id_venta = '$idVenta'";
$result = mysqli_query($open_connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $venta = mysqli_fetch_assoc($result);
} else {
    $_SESSION['message'] = "Venta no encontrada.";
    $_SESSION['message_type'] = "danger";
    header("Location: all-orders.php");
    exit();
}

// Obtener los detalles de la venta
$query = "SELECT dv.*, p.codigo, p.nombre FROM detalle_ventas dv 
          JOIN productos p ON dv.producto_id = p.id_producto 
          WHERE dv.venta_id = '$idVenta'";
$result = mysqli_query($open_connection, $query);

$detalles = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $detalles[] = $row;
    }
}

include "inc/header.php"; 
?>

<body>
    <div class="wrapper">
        <?php include "inc/sidebar.php"; ?>
        <div class="main">
            <?php include "inc/navbar.php"; ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3 text-center">Editar Venta</h1>
                    <div class="row">
                        <div class="col-12 col-md-10 offset-md-1 col-xl-8 offset-xl-2">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Editar Venta</h5>
                                    <h6 class="card-subtitle text-muted">Modifique los detalles de la venta a continuación.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="edit-order" action="inc/update-order.php" method="POST">
                                        <input type="hidden" name="id_venta" value="<?php echo $idVenta; ?>">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">T. Documento</label>
                                                <input type="text" class="form-control" value="DNI" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">N° Documento</label>
                                                <input type="number" id="dni" name="dni" class="form-control" value="<?php echo $venta['dni']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Nombres</label>
                                                <input type="text" id="nombres" name="nombres" class="form-control" value="<?php echo $venta['nombres']; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Apellidos</label>
                                                <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?php echo $venta['apellidos']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Dirección</label>
                                                <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo $venta['direccion']; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Fecha</label>
                                                <input type="date" name="fecha" class="form-control" value="<?php echo $venta['fecha_venta']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio Unitario</th>
                                                        <th>Subtotal</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-list">
                                                    <?php
                                                    foreach ($detalles as $detalle) {
                                                        echo "<tr>";
                                                        echo "<td>{$detalle['codigo']}</td>";
                                                        echo "<td>{$detalle['nombre']}</td>";
                                                        echo "<td><input type='number' class='form-control' value='{$detalle['cantidad']}' min='1' onchange='updateQuantity(this, {$detalle['precio_unitario']})'></td>";
                                                        echo "<td>{$detalle['precio_unitario']}</td>";
                                                        echo "<td class='subtotal'>" . ($detalle['cantidad'] * $detalle['precio_unitario']) . "</td>";
                                                        echo '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(this)">Eliminar</button></td>';
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label class="form-label">Subtotal</label>
                                                <input type="text" id="subtotal" name="subtotal" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-label">IGV (18%)</label>
                                                <input type="text" id="igv" name="igv" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-label">Total</label>
                                                <input type="text" id="total" name="total" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Actualizar Venta</button>
                                    </form>
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
        document.addEventListener('DOMContentLoaded', (event) => {
            let productList = <?php echo json_encode($detalles); ?>;

            function updateTotals() {
                let subtotal = 0;
                productList.forEach(product => subtotal += product.cantidad * product.precio_unitario);
                const igv = subtotal * 0.18;
                const total = subtotal + igv;

                document.getElementById('subtotal').value = subtotal.toFixed(2);
                document.getElementById('igv').value = igv.toFixed(2);
                document.getElementById('total').value = total.toFixed(2);
            }

            window.removeProduct = (element) => {
                const row = element.closest('tr');
                const index = Array.from(row.parentElement.children).indexOf(row);
                productList.splice(index, 1);
                row.remove();
                updateTotals();
            };

            window.updateQuantity = (element, price) => {
                const row = element.closest('tr');
                const index = Array.from(row.parentElement.children).indexOf(row);
                const newQuantity = parseInt(element.value);
                if (newQuantity > 0) {
                    productList[index].cantidad = newQuantity;
                    row.querySelector('.subtotal').innerText = (newQuantity * price).toFixed(2);
                    updateTotals();
                }
            };

            document.getElementById('edit-order').addEventListener('submit', function (e) {
                const productosField = document.createElement('input');
                productosField.type = 'hidden';
                productosField.name = 'productos';
                productosField.value = JSON.stringify(productList);
                this.appendChild(productosField);
            });

            updateTotals();
        });
    </script>
</body>
</html>

<?php
include "inc/close-connection.php";
?>
