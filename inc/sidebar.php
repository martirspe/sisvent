<nav class="sidebar sidebar-sticky">
    <div class="sidebar-content  js-simplebar">
        <a class="sidebar-brand" href="index.php">
            <i class="align-middle" data-feather="layers"></i>
            <span class="align-middle">SISVENT</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Principal
            </li>
            <li class="sidebar-item active">
                <a href="#dashboards" data-toggle="collapse" class="font-weight-bold sidebar-link">
                    <i class="align-middle" data-feather="home"></i> <span class="align-middle">Escritorio</span>
                    <!-- <span class="sidebar-badge badge badge-primary">14</span> -->
                </a>
                <ul id="dashboards" class="sidebar-dropdown list-unstyled collapse show">
                    <li class="sidebar-item active"><a class="sidebar-link" href="index.php">Default</a></li>
                </ul>
            </li>
            <li class="sidebar-header">
                RR:HH
            </li>

            <li class="sidebar-item">
                <a href="#users" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Usuarios</span>
                </a>
                <ul id="users" class="sidebar-dropdown list-unstyled collapse">
                    <li class="sidebar-item"><a class="sidebar-link" href="add-user.php">Añadir</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="all-users.php">Ver Todo</a></li>
                </ul>
            </li>
            <li class="sidebar-header">
                Inventario
            </li>
            <li class="sidebar-item">
                <a href="#categorias" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Categorías</span>
                </a>
                <ul id="categorias" class="sidebar-dropdown list-unstyled collapse">
                    <li class="sidebar-item"><a class="sidebar-link" href="add-category.php">Añadir</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="all-categories.php">Ver Todo</a></li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a href="#productos" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Productos</span>
                </a>
                <ul id="productos" class="sidebar-dropdown list-unstyled collapse">
                    <li class="sidebar-item"><a class="sidebar-link" href="add-product.php">Añadir</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="all-products.php">Ver Todo</a></li>
                </ul>
            </li>
            <?php
            // Verificar el rol del usuario actual
            if ($_SESSION['rol_id'] == 1) {
                // Solo mostrar este contenido si el usuario tiene el rol_id 1 (Administrador).
            echo '
                <li class="sidebar-header">
                    Ventas
                </li>
                <li class="sidebar-item">
                    <a href="#produccion" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                        <i class="align-middle" data-feather="package"></i> <span class="align-middle">Ventas</span>
                    </a>
                    <ul id="produccion" class="sidebar-dropdown list-unstyled collapse">
                        <li class="sidebar-item"><a class="sidebar-link" href="add-order.php">Añadir</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="all-orders.php">Ver Todo</a></li>
                    </ul>
                </li> 
                <li class="sidebar-header">
                    Reportes
                </li>
                <li class="sidebar-item">
                    <a href="#ventas" data-toggle="collapse" class="font-weight-bold sidebar-link collapsed">
                        <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Ventas</span>
                    </a>
                <ul id="ventas" class="sidebar-dropdown list-unstyled collapse">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Ver Todo</a></li>
                </ul>
                </li>
                ';
            } ?>
        </ul>
    </div>
</nav>