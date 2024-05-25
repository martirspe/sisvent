<nav class="navbar navbar-expand navbar-light bg-white sticky-top">
    <a class="sidebar-toggle d-flex mr-3">
        <i class="align-self-center" data-feather="menu"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle ml-2 d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                    <div class="position-relative">
                        <i class="align-middle mt-n1" data-feather="settings"></i>
                    </div>
                </a>
                <a class="nav-link nav-link-user dropdown-toggle d-none d-sm-inline-block" href="#"
                    data-toggle="dropdown">
                    <img src="<?php echo $_SESSION['imagen']; ?>" class="avatar img-fluid rounded mr-1"
                        alt="<?php echo $_SESSION['nombres'] ?> <?php echo $_SESSION['apellidos'] ?>" /> <span
                        class="text-dark"><?php echo $_SESSION['nombres'] ?> <?php echo $_SESSION['apellidos'] ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="inc/close.php">Cerrar Sesión</a>
                </div>
            </li>
        </ul>
    </div>
</nav>