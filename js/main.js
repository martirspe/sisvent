$(document).ready(function () {

    // LOGIN
    /* var loginForm = $("#login");
    loginForm.submit(function (event) {
        event.preventDefault(); // Evita el envío del formulario predeterminado

        var formData = new FormData(loginForm[0]);

        $.ajax({
            url: loginForm.attr("action"),
            type: loginForm.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-login").html("Iniciando sesión...");
            },
            success: function (result) {
                if (result.trim() === '1') { // Verifica si el resultado es '1' (éxito)
                    $("#success-login").html("Usuario autenticado correctamente. Redireccionando...");
                    setTimeout(function () {
                        window.location.href = 'index.php'; // Redirige a la página de inicio de sesión
                    }, 2000);
                } else {
                    $("#success-login").html(result);
                    $("#success-login").fadeIn("slow");
                    $("#success-login").delay(2000).fadeOut("slow");
                }
            },
            error: function () {
                $("#success-login").html("Error al procesar la solicitud.");
                $("#success-login").fadeIn("slow");
                $("#success-login").delay(2000).fadeOut("slow");
            }
        });
    }); */

    // REGISTER
    var registerForm = $("#register");
    registerForm.submit(function (event) {
        event.preventDefault(); // Evita el envío del formulario predeterminado

        var formData = new FormData(registerForm[0]);

        $.ajax({
            url: registerForm.attr("action"),
            type: registerForm.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-register").html("Guardando usuario...").fadeIn("slow");
            },
            success: function (response) {
                $("#success-register").html(response).delay(2000).fadeOut("slow", function () {
                    if (response.includes("correctamente")) {
                        registerForm[0].reset(); // Restablece el formulario si el usuario se guardó correctamente
                        setTimeout(function () {
                            window.location.href = 'login.php'; // Redirige a all-users.php
                        }, 1000);
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Muestra cualquier error en la consola del navegador
            }
        });
    });

    // INSERT USER
    var addUser = $("#add-user");
    addUser.submit(function (event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        var formData = new FormData($(this)[0]); // Obtén los datos del formulario

        $.ajax({
            url: addUser.attr("action"),
            type: addUser.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-add-user").html("Guardando usuario...").fadeIn("slow");
            },
            success: function (response) {
                $("#success-add-user").html(response).delay(2000).fadeOut("slow", function () {
                    if (response.includes("correctamente")) {
                        addUser[0].reset(); // Restablece el formulario si el usuario se guardó correctamente
                        setTimeout(function () {
                            window.location.href = 'all-users.php'; // Redirige a all-users.php
                        }, 1000);
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Muestra cualquier error en la consola del navegador
            }
        });
    });

    // UPDATE USER
    var updateUser = $("#update-user");
    updateUser.bind("submit", function () {

        var formData = new FormData($("#update-user")[0]);

        $.ajax({
            url: updateUser.attr("action"),
            type: updateUser.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-update-user").html("Actualizando usuario...");
            },
            success: function (result) {
                $("#success-update-user").html(result);
                $("#success-update-user").fadeIn("slow");
                $("#success-update-user").delay(2000).fadeOut("slow");
                /* setTimeout("location.href = 'all-users.php'", 2000); */
            }
        });

        return false;
    });

    // DELETE USER
    $(document).on("click", ".link-delete", function (e) {
        // Con esto detenemos la función nativa del selector
        e.preventDefault();
        e.stopPropagation();

        // Recuperamos el ID del atributo data-id
        let id = $(this).data('id');

        // Enviamos el AJAX
        $.ajax({
            type: "GET",
            url: "inc/delete-user.php",
            data: { id_usuario: id },
            beforeSend: function () {
                $("#success-delete-user").html("Eliminando usuario...");
            },
            success: function (result) {
                $("#success-delete-user").html(result);
                $("#success-delete-user").fadeIn("slow");
                $("#success-delete-user").delay(2000).fadeOut("slow");
            },
            complete: function () {
                setTimeout("location.reload()", 2000);
            }
        });
    });

    // INSERT PRODUCT
    var addProduct = $("#add-product");
    addProduct.bind("submit", function () {

        var formData = new FormData($("#add-product")[0]);

        $.ajax({
            url: addProduct.attr("action"),
            type: addProduct.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-add-product").html("Guardando producto...");
            },
            success: function (result) {
                $("#success-add-product").html(result);
                $("#success-add-product").fadeIn("slow");
                $("#success-add-product").delay(2000).fadeOut("slow");
            }
        });

        return false;
    });

    // UPDATE PRODUCT
    var updateProduct = $("#update-product");
    updateProduct.bind("submit", function () {

        var formData = new FormData($("#update-product")[0]);

        $.ajax({
            url: updateProduct.attr("action"),
            type: updateProduct.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-update-product").html("Actualizando producto...");
            },
            success: function (result) {
                $("#success-update-product").html(result);
                $("#success-update-product").fadeIn("slow");
                $("#success-update-product").delay(2000).fadeOut("slow");
            }
        });

        return false;
    });

    // DELETE PRODUCT
    $(document).on("click", "#delete-product", function (e) {
        //Con esto detenemos la función nativa del selector
        e.preventDefault();
        e.stopPropagation();

        //Recuperamos el ID del atributo data-id
        let id = $(this).data('id');

        //Enviamos el AJAX
        $.ajax({
            type: "GET",
            url: "inc/delete-product.php",
            data: { id },
            beforeSend: function () {
                $("#success-delete-product").html("Eliminando producto...");
            },
            success: function (result) {
                $("#success-delete-product").html(result);
                $("#success-delete-product").fadeIn("slow");
                $("#success-delete-product").delay(2000).fadeOut("slow");
            },
            complete: function () {
                setTimeout("location.reload()", 2000);
            }
        });
    });


    // INSERT CATEGORY
    var addCategory = $("#add-category");
    addCategory.bind("submit", function () {

        var formData = new FormData($("#add-category")[0]);

        $.ajax({
            url: addCategory.attr("action"),
            type: addCategory.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-add-category").html("Guardando categoría...");
            },
            success: function (result) {
                $("#success-add-category").html(result);
                $("#success-add-category").fadeIn("slow");
                $("#success-add-category").delay(2000).fadeOut("slow");
            }
        });

        return false;
    });

    // UPDATE CATEGORY
    var updateCategory = $("#update-category");
    updateCategory.bind("submit", function () {

        var formData = new FormData($("#update-category")[0]);

        $.ajax({
            url: updateCategory.attr("action"),
            type: updateCategory.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-update-category").html("Actualizando categoría...");
            },
            success: function (result) {
                $("#success-update-category").html(result);
                $("#success-update-category").fadeIn("slow");
                $("#success-update-category").delay(2000).fadeOut("slow");
            }
        });

        return false;
    });

    // DELETE CATEGORY
    $(document).on("click", "#delete-category", function (e) {
        //Con esto detenemos la función nativa del selector
        e.preventDefault();
        e.stopPropagation();

        //Recuperamos el ID del atributo data-id
        let id = $(this).data('id');

        //Enviamos el AJAX
        $.ajax({
            type: "GET",
            url: "inc/delete-category.php",
            data: { id },
            beforeSend: function () {
                $("#success-delete-category").html("Eliminando producto...");
            },
            success: function (result) {
                $("#success-delete-category").html(result);
                $("#success-delete-category").fadeIn("slow");
                $("#success-delete-category").delay(2000).fadeOut("slow");
            },
            complete: function () {
                setTimeout("location.reload()", 2000);
            }
        });
    });

    // INSERT BRAND
    var addBrand = $("#add-brand");
    addBrand.bind("submit", function () {

        var formData = new FormData($("#add-brand")[0]);

        $.ajax({
            url: addBrand.attr("action"),
            type: addBrand.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-add-brand").html("Guardando categoría...");
            },
            success: function (result) {
                $("#success-add-brand").html(result);
                $("#success-add-brand").fadeIn("slow");
                $("#success-add-brand").delay(2000).fadeOut("slow");
            }
        });

        return false;
    });

    // UPDATE BRAND
    var updateBrand = $("#update-brand");
    updateBrand.bind("submit", function () {

        var formData = new FormData($("#update-brand")[0]);

        $.ajax({
            url: updateBrand.attr("action"),
            type: updateBrand.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-update-brand").html("Actualizando categoría...");
            },
            success: function (result) {
                $("#success-update-brand").html(result);
                $("#success-update-brand").fadeIn("slow");
                $("#success-update-brand").delay(2000).fadeOut("slow");
            }
        });

        return false;
    });

    // DELETE CATEGORY
    $(document).on("click", "#delete-brand", function (e) {
        //Con esto detenemos la función nativa del selector
        e.preventDefault();
        e.stopPropagation();

        //Recuperamos el ID del atributo data-id
        let id = $(this).data('id');

        //Enviamos el AJAX
        $.ajax({
            type: "GET",
            url: "inc/delete-brand.php",
            data: { id },
            beforeSend: function () {
                $("#success-delete-brand").html("Eliminando producto...");
            },
            success: function (result) {
                $("#success-delete-brand").html(result);
                $("#success-delete-brand").fadeIn("slow");
                $("#success-delete-brand").delay(2000).fadeOut("slow");
            },
            complete: function () {
                setTimeout("location.reload()", 2000);
            }
        });
    });

    // INSERT CLIENT
    var addClient = $("#add-client");
    addClient.bind("submit", function () {

        var formData = new FormData($("#add-client")[0]);

        $.ajax({
            url: addClient.attr("action"),
            type: addClient.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-add-client").html("Guardando cliente...");
            },
            success: function (result) {
                $("#success-add-client").html(result);
                $("#success-add-client").fadeIn("slow");
                $("#success-add-client").delay(2000).fadeOut("slow");
                setTimeout("location.href = 'all-clients.php'", 2000);
            }
        });

        return false;
    });

    // UPDATE CLIENT
    var updateClient = $("#update-client");
    updateClient.bind("submit", function () {

        var formData = new FormData($("#update-client")[0]);

        $.ajax({
            url: updateClient.attr("action"),
            type: updateClient.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-update-client").html("Actualizando cliente...");
            },
            success: function (result) {
                $("#success-update-client").html(result);
                $("#success-update-client").fadeIn("slow");
                $("#success-update-client").delay(2000).fadeOut("slow");
                setTimeout("location.href = 'all-clients.php'", 2000);
            }
        });

        return false;
    });

    // DELETE CLIENT
    $(document).on("click", "#delete-client", function (e) {
        //Con esto detenemos la función nativa del selector
        e.preventDefault();
        e.stopPropagation();

        //Recuperamos el ID del atributo data-id
        let id = $(this).data('id');

        //Enviamos el AJAX
        $.ajax({
            type: "GET",
            url: "inc/delete-client.php",
            data: { id },
            beforeSend: function () {
                $("#success-delete-client").html("Eliminando cliente...");
            },
            success: function (result) {
                $("#success-delete-client").html(result);
                $("#success-delete-client").fadeIn("slow");
                $("#success-delete-client").delay(2000).fadeOut("slow");
            },
            complete: function () {
                setTimeout("location.reload()", 2000);
            }
        });
    });

    // INSERT ORDER
    var addOrder = $("#add-order");
    addOrder.bind("submit", function () {

        var formData = new FormData($("#add-order")[0]);

        $.ajax({
            url: addOrder.attr("action"),
            type: addOrder.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-add-order").html("Generando orden...");
            },
            success: function (result) {
                $("#success-add-order").html(result);
                $("#success-add-order").fadeIn("slow");
                $("#success-add-order").delay(2000).fadeOut("slow");
                setTimeout("location.href = 'all-orders.php'", 2000);
            }
        });

        return false;
    });

    // UPDATE ORDER
    var updateOrder = $("#update-order");
    updateOrder.bind("submit", function () {

        var formData = new FormData($("#update-order")[0]);

        $.ajax({
            url: updateOrder.attr("action"),
            type: updateOrder.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#success-update-order").html("Actualizando orden...");
            },
            success: function (result) {
                $("#success-update-order").html(result);
                $("#success-update-order").fadeIn("slow");
                $("#success-update-order").delay(2000).fadeOut("slow");
                setTimeout("location.href = 'all-orders.php'", 2000);
            }
        });

        return false;
    });

    // DELETE ORDER
    $(document).on("click", "#delete-order", function (e) {
        //Con esto detenemos la función nativa del selector
        e.preventDefault();
        e.stopPropagation();

        //Recuperamos el ID del atributo data-id
        let id = $(this).data('id');

        //Enviamos el AJAX
        $.ajax({
            type: "GET",
            url: "inc/delete-order.php",
            data: { id },
            beforeSend: function () {
                $("#success-delete-order").html("Eliminando orden...");
            },
            success: function (result) {
                $("#success-delete-order").html(result);
                $("#success-delete-order").fadeIn("slow");
                $("#success-delete-order").delay(2000).fadeOut("slow");
            },
            complete: function () {
                setTimeout("location.reload()", 2000);
            }
        });
    });

});