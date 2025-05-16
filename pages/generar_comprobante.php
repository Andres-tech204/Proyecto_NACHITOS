<?php
require_once '../librerias/dompdf/autoload.inc.php';
require_once '../uploads/mail_config.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$nombre = $_GET['nombre'] ?? 'Cliente Desconocido';
$email = $_GET['email'] ?? 'correo@ejemplo.com';
$producto = $_GET['producto'] ?? 'Producto Desconocido';
$precio = $_GET['precio'] ?? '0.00';
$fecha = date("d/m/Y H:i");
$mensaje = htmlspecialchars($_GET['mensaje'] ?? 'Mensaje opcional');

$html = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                position: relative;
                background-image: url('C:/xampp/htdocs/nachitos/imagenes/LogoNachitos.png');
                background-size: 300px;
                background-position: center;
                background-repeat: no-repeat;
                opacity: 0.9;
            }
        </style>
    </head>
    <body>
        <h1 style='text-align:center;'>Comprobante de Pedido</h1>
        <hr>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Producto:</strong> $producto</p>
        <p><strong>Precio:</strong> $precio CLP</p>
        <p><strong>Fecha:</strong> $fecha</p>
        <p><strong>Mensaje:</strong> $mensaje</p>
        <p>Nos pondremos en contacto contigo de inmediato!</p>
    </body>
    </html>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdf_output = $dompdf->output();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host    = $smtp_host;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_pass;
    $mail->SMTPSecure = 'tls';
    $mail->Port     = 587;

    $mail->setFrom($smtp_user, 'Nachitos');

    $mail->addAddress($email, $nombre);
    $mail->addAddress('nachitos@correo.cl', 'Administrador');

    $mail->Subject = 'Comprobante de pedido - Nachitos';
    $mail->Body = "Hola $nombre, \n\nAdjuntamos tu comprobante de pedido. \nGracias por cotizar en Nachitos";

    $mail->addStringAttachment($pdf_output, "Comprobante_$nombre.pdf");

    $mail->send();
    echo "Correo enviado correctamente con comprobante.";
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
}