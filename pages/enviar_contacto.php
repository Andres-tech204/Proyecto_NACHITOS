<?php

require '../uploads/mail_config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$nombre   = $_POST['nombre'] ?? '';
$email    = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$lugar    = $_POST['lugar'] ?? '';
$mensaje  = $_POST['mensaje'] ?? '';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host    = $smtp_host;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_pass;
    $mail->SMTPSecure = 'tls';
    $mail->Port     = 587;
    

    $mail->setFrom($smtp_user, 'Web Nachitos');
    $mail->addAddress('andres.ignacio204@gmail.com', 'Administrador Nachitos');

    $mail->isHTML(true);
    $mail->Subject = 'Nuevo contacto desde la web Nachitos';
    $mail->Body    = "
        <h3>Nuevo mensaje de contacto</h3>
        <p><strong>Nombre:</strong> {$nombre}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Teléfono:</strong> {$telefono}</p>
        <p><strong>Lugar:</strong> {$lugar}</p>
        <p><strong>Mensaje:</strong><br>{$mensaje}</p>
    ";

    $mail->send();
    echo "<script>alert('Mensaje enviado correctamente.');window.location.href='/nachitos/pages/contacto.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('Error al enviar el mensaje: {$mail->ErrorInfo}');window.location.href='/nachitos/pages/contacto.php';</script>";
}
?>