<?php

include "open-connection.php";

// Recepcionando datos del formulario.
$id = $_GET['id'];

// Verificando si la marca tiene productos asignados.
$query = "SELECT COUNT(*) AS total FROM productos WHERE categoria_id = '$id'";
$results = mysqli_query($open_connection, $query);
$row = mysqli_fetch_assoc($results);

if ($row['total'] > 0) {
    // La marca tiene productos asignados
    echo '
        <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
            </div>
            <div class="alert-message">
                No se puede eliminar la marca porque tiene productos asignados.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
} else {
    // Intentar eliminar la marca
    $query = "DELETE FROM marcas WHERE id_marca = '$id'";
    $results = mysqli_query($open_connection, $query);

    if ($results == false) {
        echo '
            <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    Error al eliminar marca.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    } else {
        echo '
            <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    Marca eliminada correctamente.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    }
}

include "close-connection.php";
?>
