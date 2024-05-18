<?php
include "open-connection.php";

// Recepcionando datos del formulario.
$id = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

// Verificar que el usuario no tenga rol de administrador (rol_id = 1)
$query = "SELECT rol_id FROM usuarios WHERE id_usuario = ?";
$stmt = mysqli_prepare($open_connection, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $rol_id);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($rol_id == 1) {
    echo '
        <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                <strong>Alerta!</strong> No puedes eliminar a un administrador.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
} else {
    // Eliminar el usuario de la base de datos
    $query = "UPDATE usuarios SET estado = 0 WHERE id_usuario = ?";
    $stmt = mysqli_prepare($open_connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        echo '
            <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>Alerta!</strong> Usuario eliminado correctamente.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } else {
        echo '
            <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    <strong>Alerta!</strong> Error al eliminar usuario.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    }
}

include "close-connection.php";
?>