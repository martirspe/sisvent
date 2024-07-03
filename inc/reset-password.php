<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir archivo de conexión
include "open-connection.php";

// Incluir archivos de PHPMailer
require '../vendor/autoload.php'; // Asegúrate de que la ruta es correcta

// Inicializando Zona Horaria.
date_default_timezone_set('America/Bogota');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibió un correo electrónico
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        // Filtrar y limpiar el correo electrónico
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Validar el correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '
                <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                    <div class="alert-icon">
                        <i class="far fa-fw fa-bell"></i>
                    </div>
                    <div class="alert-message">
                        La dirección de correo electrónico no es válida.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            ';
        } else {
            // Verificar si el correo electrónico existe en la base de datos
            $query = "SELECT id_usuario FROM usuarios WHERE email = ?";
            $stmt = mysqli_prepare($open_connection, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            // Si el correo electrónico existe
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Generar un token único
                $token = bin2hex(random_bytes(32));

                // Generar una clave hash con el token y una clave secreta
                $secret_key = 'albz9133@PWD'; // Debes establecer una clave secreta segura
                $hash = hash_hmac('sha256', $token, $secret_key);

                // Envía el correo electrónico con el enlace de restablecimiento de contraseña
                $reset_link = "http://localhost/sisvent/new-password.php?token=" . $token . "&hash=" . $hash . "&email=" . urlencode($email);
                $subject = "Restablecimiento de contraseña";
                $message = "Hola, para restablecer tu contraseña, haz clic en el siguiente enlace: <a href='$reset_link'>$reset_link</a>";

                // Configuración de PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Configuración del servidor
                    $mail->isSMTP();
                    $mail->Host = 'mail.marrso.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'mrojas@marrso.com';
                    $mail->Password = 'albz9131@MS';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Configuración del correo
                    $mail->setFrom('admin@marrso.com', 'SISVENT');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = $subject;
                    $mail->Body    = $message;

                    // Enviar correo
                    $mail->send();
                    echo '
                        <div class="alert alert-success alert-outline-coloured alert-dismissible" role="alert">
                            <div class="alert-icon">
                                <i class="far fa-fw fa-bell"></i>
                            </div>
                            <div class="alert-message">
                                Se ha enviado un correo electrónico con instrucciones para restablecer tu contraseña.
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    ';
                } catch (Exception $e) {
                    echo '
                        <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                            <div class="alert-icon">
                                <i class="far fa-fw fa-bell"></i>
                            </div>
                            <div class="alert-message">
                                Error al enviar el correo electrónico. Por favor, intenta de nuevo. Error: ', $mail->ErrorInfo, '
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    ';
                }
            } else {
                // Si no se encuentra el correo electrónico en la base de datos, mostrar un mensaje de error
                echo '
                    <div class="alert alert-danger alert-outline-coloured alert-dismissible" role="alert">
                        <div class="alert-icon">
                            <i class="far fa-fw fa-bell"></i>
                        </div>
                        <div class="alert-message">
                            El correo electrónico proporcionado no está registrado en nuestro sistema.
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                ';
            }
        }
    } else {
        // Si no se proporcionó un correo electrónico, mostrar un mensaje de error
        echo '
            <div class="alert alert-warning alert-outline-coloured alert-dismissible" role="alert">
                <div class="alert-icon">
                    <i class="far fa-fw fa-bell"></i>
                </div>
                <div class="alert-message">
                    Por favor, introduce tu correo electrónico.
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        ';
    }
}

include "close-connection.php";
?>
