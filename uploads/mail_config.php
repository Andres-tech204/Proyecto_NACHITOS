<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function enviarCorreo($para, $asunto, $cuerpo) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'nachitosbot@gmail.com'; // Cambia esto
        $mail->Password = 'uioy zxpk yabh klcq'; // Usa app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('nachitosbot@gmail.com', 'Nachitos'); // Remitente
        $mail->addAddress($para); // Destinatario

        $mail->isHTML(false);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}
