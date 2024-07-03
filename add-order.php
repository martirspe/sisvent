<?php
include "inc/open-connection.php";
session_start();

// Verificar si el usuario está activo
if (empty($_SESSION['active'])) {
    header("location: login.php");
    exit();
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
                    <h1 class="h3 mb-3 text-center">Registro de Venta</h1>
                    <div class="row">
                        <div class="col-12 col-md-10 offset-md-1 col-xl-8 offset-xl-2">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Nueva Venta</h5>
                                    <h6 class="card-subtitle text-muted">Ingrese los detalles de la venta a continuación.</h6>
                                </div>
                                <div class="card-body">
                                    <form id="add-order" action="inc/add-order.php" method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">T. Documento</label>
                                                <input type="text" class="form-control" value="DNI" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">N° Documento</label>
                                                <input type="number" id="dni" name="dni" class="form-control" required>
                                                <button type="button" id="buscar-cliente" class="btn btn-secondary mt-2">Buscar Cliente</button>
                                                <a href="add-user.php" class="btn btn-primary mt-2">Agregar Cliente</a>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Nombres</label>
                                                <input type="text" id="nombres" name="nombres" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Apellidos</label>
                                                <input type="text" id="apellidos" name="apellidos" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Dirección</label>
                                                <input type="text" id="direccion" name="direccion" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Fecha</label>
                                                <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Buscar Producto</label>
                                                <input type="text" id="buscar_producto" class="form-control" placeholder="Nombre del producto">
                                                <div id="sugerencias-producto" class="list-group"></div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Código</label>
                                                <input type="text" id="codigo_producto" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Cantidad</label>
                                                <input type="number" id="cantidad_producto" class="form-control" min="1">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Precio</label>
                                                <input type="number" id="precio_producto" class="form-control" step="0.01" min="0" readonly>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <button type="button" class="btn btn-primary" id="add-product-button">Añadir Producto</button>
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
                                                    <!-- Productos añadidos aparecerán aquí -->
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
                                                <input type="text" id="igv" name="igv" class="form-control" value="18%" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-label">Total</label>
                                                <input type="text" id="total" name="total" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Registrar Venta</button>
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
            let productList = [];

            document.getElementById('buscar-cliente').addEventListener('click', () => {
                const dni = document.getElementById('dni').value;
                if (dni) {
                    fetch('inc/search-client.php?dni=' + dni)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('nombres').value = data.nombres;
                                document.getElementById('apellidos').value = data.apellidos;
                                document.getElementById('direccion').value = data.direccion;
                            } else {
                                alert('Cliente no encontrado.');
                            }
                        });
                }
            });

            document.getElementById('buscar_producto').addEventListener('input', () => {
                const query = document.getElementById('buscar_producto').value;
                if (query.length > 2) {
                    fetch('inc/search-product.php?query=' + query)
                        .then(response => response.json())
                        .then(data => {
                            const suggestions = document.getElementById('sugerencias-producto');
                            suggestions.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(producto => {
                                    const item = document.createElement('a');
                                    item.classList.add('list-group-item', 'list-group-item-action');
                                    item.textContent = producto.nombre;
                                    item.addEventListener('click', () => {
                                        document.getElementById('codigo_producto').value = producto.codigo;
                                        document.getElementById('precio_producto').value = producto.precio;
                                        document.getElementById('buscar_producto').value = producto.nombre;
                                        suggestions.innerHTML = '';
                                    });
                                    suggestions.appendChild(item);
                                });
                            }
                        });
                }
            });

            document.getElementById('add-product-button').addEventListener('click', () => {
                const codigo = document.getElementById('codigo_producto').value;
                const nombre = document.getElementById('buscar_producto').value;
                const cantidad = parseInt(document.getElementById('cantidad_producto').value);
                const precio = parseFloat(document.getElementById('precio_producto').value);

                if (codigo && nombre && cantidad > 0 && precio > 0) {
                    const subtotal = cantidad * precio;
                    productList.push({ codigo, nombre, cantidad, precio, subtotal });

                    updateProductList();
                    updateTotals();
                } else {
                    alert("Por favor, completa todos los campos del producto.");
                }
            });

            function updateProductList() {
                const productTableBody = document.getElementById('product-list');
                productTableBody.innerHTML = '';
                productList.forEach((product, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.codigo}</td>
                        <td>${product.nombre}</td>
                        <td><input type="number" class="form-control" value="${product.cantidad}" min="1" onchange="updateQuantity(${index}, this.value)"></td>
                        <td>${product.precio.toFixed(2)}</td>
                        <td>${product.subtotal.toFixed(2)}</td>
                        <td><button type="button" class="btn btn-danger" onclick="removeProduct(${index})">Eliminar</button></td>
                    `;
                    productTableBody.appendChild(row);
                });
            }

            function updateTotals() {
                let subtotal = 0;
                productList.forEach(product => subtotal += product.subtotal);
                const igv = subtotal * 0.18;
                const total = subtotal + igv;

                document.getElementById('subtotal').value = subtotal.toFixed(2);
                document.getElementById('igv').value = igv.toFixed(2);
                document.getElementById('total').value = total.toFixed(2);
            }

            window.removeProduct = (index) => {
                productList.splice(index, 1);
                updateProductList();
                updateTotals();
            };

            window.updateQuantity = (index, newQuantity) => {
                if (newQuantity > 0) {
                    productList[index].cantidad = parseInt(newQuantity);
                    productList[index].subtotal = productList[index].cantidad * productList[index].precio;
                    updateProductList();
                    updateTotals();
                }
            };

            document.getElementById('add-order').addEventListener('submit', function (e) {
                if (productList.length === 0) {
                    e.preventDefault();
                    alert("Debe añadir al menos un producto a la venta.");
                } else {
                    const productosField = document.createElement('input');
                    productosField.type = 'hidden';
                    productosField.name = 'productos';
                    productosField.value = JSON.stringify(productList);
                    this.appendChild(productosField);
                }
            });
        });
    </script>
</body>
</html>
