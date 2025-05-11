<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../uploads/PHPMailer/PHPMailer.php';
require '../uploads/PHPMailer/SMTP.php';
require '../uploads/PHPMailer/Exception.php';

include('../uploads/conexion.php');
include('../includes/header.php');

$mensaje = "";
$exito = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $mensaje_cliente = $_POST['mensaje'] ?? '';

    if (empty($nombre) || empty($correo) || empty($telefono)) {
        $mensaje = "Todos los campos obligatorios deben estar completos.";
    } else {
        $sql = "INSERT INTO pedidos (nombre, correo, telefono, mensaje, estado, fecha_pedido) 
                VALUES ('$nombre', '$correo', '$telefono', '$mensaje_cliente', 'pendiente', NOW())";

        if ($conn->query($sql) === TRUE) {
            $id_pedido = $conn->insert_id;

            foreach ($_SESSION['pedido'] as $item) {
                $id_producto = $item['id'];
                $cantidad = $item['cantidad'];
                $precio = $item['precio'];

                $sql_detalle = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) 
                                VALUES ('$id_pedido', '$id_producto', '$cantidad', '$precio')";

                if (!$conn->query($sql_detalle)) {
                    $mensaje = "Error al guardar el detalle del pedido: " . $conn->error;
                    break;
                }
            }

            if (empty($mensaje)) {
                $mail = new PHPMailer(true);
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'nachitosbot@gmail.com';
                    $mail->Password = 'wgya gtce rgav mjyv';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                
                    $mail->setFrom('nachitosbot@gmail.com', 'Nachitos');
                    $mail->addAddress($correo, $nombre); 
                
                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmacion de tu pedido - Nachitos';
                
                    $contenido = "<h3>Hola $nombre, gracias por tu pedido</h3>";
                    $contenido .= "<p>Detalles del pedido:</p><ul>";
                
                    foreach ($_SESSION['pedido'] as $item) {
                        $contenido .= "<li>" . $item['nombre'] . " - Cantidad: " . $item['cantidad'] . " - Precio: $" . number_format($item['precio'], 0, ',', '.') . "</li>";
                    }
                
                    $contenido .= "</ul><p>Nos pondremos en contacto contigo pronto para coordinar la entrega.</p>";
                    $mail->Body = $contenido;
                
                    $mail->send(); 
                
                    $mail_admin = new PHPMailer(true);
                    $mail_admin->isSMTP();
                    $mail_admin->Host = 'smtp.gmail.com';
                    $mail_admin->SMTPAuth = true;
                    $mail_admin->Username = 'nachitosbot@gmail.com';
                    $mail_admin->Password = 'wgya gtce rgav mjyv';
                    $mail_admin->SMTPSecure = 'tls';
                    $mail_admin->Port = 587;
                
                    $mail_admin->setFrom('nachitosbot@gmail.com', 'Nachitos');
                    $mail_admin->addAddress('hornosnachitosvillarrica@gmail.com', 'Administrador'); 
                
                    $mail_admin->isHTML(true);
                    $mail_admin->Subject = 'Nuevo pedido recibido - Nachitos';
                
                    $admin_contenido = "<h3>Nuevo pedido recibido</h3>";
                    $admin_contenido .= "<p><strong>Nombre:</strong> $nombre</p>";
                    $admin_contenido .= "<p><strong>Correo:</strong> $correo</p>";
                    $admin_contenido .= "<p><strong>Teléfono:</strong> $telefono</p>";
                    $admin_contenido .= "<p><strong>Mensaje del cliente:</strong> $mensaje_cliente</p>";
                    $admin_contenido .= "<h4>Productos:</h4><ul>";
                
                    foreach ($_SESSION['pedido'] as $item) {
                        $admin_contenido .= "<li>" . $item['nombre'] . " - Cantidad: " . $item['cantidad'] . " - Precio: $" . number_format($item['precio'], 0, ',', '.') . "</li>";
                    }
                
                    $admin_contenido .= "</ul>";
                    $mail_admin->Body = $admin_contenido;
                
                    $mail_admin->send(); 
                
                    $exito = true;
                    $mensaje = "¡Pedido enviado exitosamente! Pronto recibirás un correo de confirmación.";
                    $_SESSION['pedido'] = [];
                
                } catch (Exception $e) {
                    $mensaje = "No se pudo enviar el correo: {$mail->ErrorInfo}";
                }
                
            }
        } else {
            $mensaje = "Error al registrar el pedido: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesamiento de Pedido</title>
</head>
<body>
    <h1>Resultado del Pedido</h1>
    <p><?php echo $mensaje; ?></p>

    <?php if ($exito): ?>
        <a href="../index.php">Volver al inicio</a>
    <?php else: ?>
        <a href="../pages/pedido.php">Volver al formulario</a>
    <?php endif; ?>

    <?php include("../includes/footer.php"); ?>
</body>
</html>
