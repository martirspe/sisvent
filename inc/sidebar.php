<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

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
            <li class="sidebar-item <?= $current_page == 'index.php' ? 'active' : '' ?>">
                <a href="#dashboards" data-toggle="collapse"
                    class="font-weight-bold sidebar-link <?= $current_page == 'index.php' ? '' : 'collapsed' ?>">
                    <i class="align-middle" data-feather="home"></i> <span class="align-middle">Escritorio</span>
                </a>
                <ul id="dashboards"
                    class="sidebar-dropdown list-unstyled collapse <?= $current_page == 'index.php' ? 'show' : '' ?>">
                    <li class="sidebar-item <?= $current_page == 'index.php' ? 'active' : '' ?>"><a class="sidebar-link"
                            href="index.php">Default</a></li>
                </ul>
            </li>
            <li class="sidebar-header">
                RR:HH
            </li>

            <li class="sidebar-item <?= in_array($current_page, ['add-user.php', 'all-users.php']) ? 'active' : '' ?>">
                <a href="#users" data-toggle="collapse"
                    class="font-weight-bold sidebar-link <?= in_array($current_page, ['add-user.php', 'all-users.php']) ? '' : 'collapsed' ?>">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Usuarios</span>
                </a>
                <ul id="users"
                    class="sidebar-dropdown list-unstyled collapse <?= in_array($current_page, ['add-user.php', 'all-users.php']) ? 'show' : '' ?>">
                    <li class="sidebar-item <?= $current_page == 'add-user.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="add-user.php">Añadir</a></li>
                    <li class="sidebar-item <?= $current_page == 'all-users.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="all-users.php">Ver Todo</a></li>
                </ul>
            </li>
            <li class="sidebar-header">
                Inventario
            </li>
            <li
                class="sidebar-item <?= in_array($current_page, ['add-category.php', 'all-categories.php']) ? 'active' : '' ?>">
                <a href="#categorias" data-toggle="collapse"
                    class="font-weight-bold sidebar-link <?= in_array($current_page, ['add-category.php', 'all-categories.php']) ? '' : 'collapsed' ?>">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Categorías</span>
                </a>
                <ul id="categorias"
                    class="sidebar-dropdown list-unstyled collapse <?= in_array($current_page, ['add-category.php', 'all-categories.php']) ? 'show' : '' ?>">
                    <li class="sidebar-item <?= $current_page == 'add-category.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="add-category.php">Añadir</a></li>
                    <li class="sidebar-item <?= $current_page == 'all-categories.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="all-categories.php">Ver Todo</a></li>
                </ul>
            </li>
            <li
                class="sidebar-item <?= in_array($current_page, ['add-category.php', 'all-categories.php']) ? 'active' : '' ?>">
                <a href="#marcas" data-toggle="collapse"
                    class="font-weight-bold sidebar-link <?= in_array($current_page, ['add-category.php', 'all-categories.php']) ? '' : 'collapsed' ?>">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Marcas</span>
                </a>
                <ul id="marcas"
                    class="sidebar-dropdown list-unstyled collapse <?= in_array($current_page, ['add-brand.php', 'all-brands.php']) ? 'show' : '' ?>">
                    <li class="sidebar-item <?= $current_page == 'add-brand.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="add-brand.php">Añadir</a></li>
                    <li class="sidebar-item <?= $current_page == 'all-brands.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="all-brands.php">Ver Todo</a></li>
                </ul>
            </li>
            <li
                class="sidebar-item <?= in_array($current_page, ['add-product.php', 'all-products.php']) ? 'active' : '' ?>">
                <a href="#productos" data-toggle="collapse"
                    class="font-weight-bold sidebar-link <?= in_array($current_page, ['add-product.php', 'all-products.php']) ? '' : 'collapsed' ?>">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Productos</span>
                </a>
                <ul id="productos"
                    class="sidebar-dropdown list-unstyled collapse <?= in_array($current_page, ['add-product.php', 'all-products.php']) ? 'show' : '' ?>">
                    <li class="sidebar-item <?= $current_page == 'add-product.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="add-product.php">Añadir</a></li>
                    <li class="sidebar-item <?= $current_page == 'stock-management.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="stock-management.php">Gestión de Stock</a></li>
                    <li class="sidebar-item <?= $current_page == 'all-movements.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="all-movements.php">Movimientos de Stock</a></li>
                    <li class="sidebar-item <?= $current_page == 'all-products.php' ? 'active' : '' ?>"><a
                            class="sidebar-link" href="all-products.php">Ver Todo</a></li>
                </ul>
            </li>
            <?php
            // Verificar el rol del usuario actual
            if ($_SESSION['rol_id'] == 2) {
                // Solo mostrar este contenido si el usuario tiene el rol_id 1 (Administrador).
            echo '
                <li class="sidebar-header">
                    Ventas
                </li>
                <li class="sidebar-item '.(in_array($current_page, ["add-order.php", "all-orders.php"]) ? "active" : "").'">
                    <a href="#produccion" data-toggle="collapse" class="font-weight-bold sidebar-link '.(in_array($current_page, ["add-order.php", "all-orders.php"]) ? "" : "collapsed").'">
                        <i class="align-middle" data-feather="package"></i> <span class="align-middle">Ventas</span>
                    </a>
                    <ul id="produccion" class="sidebar-dropdown list-unstyled collapse '.(in_array($current_page, ["add-order.php", "all-orders.php"]) ? "show" : "").'">
                        <li class="sidebar-item '.($current_page == "add-order.php" ? "active" : "").'"><a class="sidebar-link" href="add-order.php">Añadir</a></li>
                        <li class="sidebar-item '.($current_page == "all-orders.php" ? "active" : "").'"><a class="sidebar-link" href="all-orders.php">Ver Todo</a></li>
                    </ul>
                </li>';
            } ?>
        </ul>
    </div>
</nav>