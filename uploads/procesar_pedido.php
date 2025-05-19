<?php
session_start();
include 'conexion.php';
include 'mail_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Acceso no permitido.";
    exit;
}

$nombre  = trim($_POST['nombre']  ?? '');
$correo  = trim($_POST['correo']  ?? '');
$telefono= trim($_POST['telefono']?? '');
$mensajeC= trim($_POST['mensaje'] ?? '');

$productos = $_POST['producto_id'] ?? [];
$cantidades= $_POST['cantidad']    ?? [];

if (count($productos) === 0) {
    echo "No se enviaron productos.";
    exit;
}


$stmt = $conn->prepare(
  "INSERT INTO pedidos
    (usuario_id, fecha_pedido, descripcion_cliente, estado, total)
   VALUES
    (NULL, NOW(), ?, 'pendiente', 0)"
);
$descripcion_cliente = "Nombre: $nombre\nCorreo: $correo\nTeléfono: $telefono\nMensaje: $mensajeC";
$stmt->bind_param("s", $descripcion_cliente);
if (!$stmt->execute()) {
    echo "Error al registrar el pedido.";
    exit;
}
$pedido_id = $conn->insert_id;


$total = 0;
for ($i = 0; $i < count($productos); $i++) {
    $pid  = intval($productos[$i]);
    $cant = intval($cantidades[$i]);

    $s = $conn->prepare(
      "SELECT nombre_producto, precio
       FROM productos
       WHERE producto_id = ?"
    );
    $s->bind_param("i", $pid);
    $s->execute();
    $res = $s->get_result();
    if ($res->num_rows !== 1) {
        echo "Producto ID $pid no encontrado.";
        exit;
    }
    $row = $res->fetch_assoc();
    $precio = $row['precio'];
    $total += $precio * $cant;

}

$u = $conn->prepare(
  "UPDATE pedidos
     SET total = ?
   WHERE pedido_id = ?"
);
$u->bind_param("di", $total, $pedido_id);
$u->execute();


$asuntoCli = "Confirmación de tu pedido en Nachitos";
$cuerpoCli = "¡Gracias por tu pedido!\n\nDetalle del pedido:\n";

for ($i = 0; $i < count($productos); $i++) {
    $pid  = intval($productos[$i]);
    $cant = intval($cantidades[$i]);

    $s = $conn->prepare(
      "SELECT nombre_producto, precio
       FROM productos
       WHERE producto_id = ?"
    );
    $s->bind_param("i", $pid);
    $s->execute();
    $res = $s->get_result();
    if ($res->num_rows !== 1) {
        echo "Producto ID $pid no encontrado.";
        exit;
    }
    $row = $res->fetch_assoc();
    $nombreProd = $row['nombre_producto'];
    $precio = $row['precio'];
    $subtotal = $precio * $cant;

    $cuerpoCli .= "- $nombre: $cant x $" . number_format($precio, 0, ',', '.') . " = $" . number_format($subtotal, 0, ',', '.') . "\n";
    
    $ins = $conn->prepare(
      "INSERT INTO detalle_pedido
         (pedido_id, producto_id, cantidad, precio_unitario)
       VALUES (?, ?, ?, ?)"
    );
    $ins->bind_param("iiid", $pedido_id, $pid, $cant, $precio);
    if (!$ins->execute()) {
        echo "Error al registrar el detalle del pedido.";
        exit;
    }
}

$cuerpoCli .= "\nTotal: $" . number_format($total, 0, ',', '.') . "\n\n$descripcion_cliente";
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';
require_once 'mail_config.php';
require_once '../librerias/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$html = "
<html>
<head>
<style>
  body {
    font-family: Arial, sans-serif;
    background-image: url('../imagenes/LogoNachitos.png');
    background-size: 300px;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
  }
  ul {
  padding-left: 20px;
  page-break-inside: avoid;
  }

</style>
</head>
<body>
  <h1 style='text-align:center;'>Comprobante de Pedido</h1>
  <hr>
  <p><strong>Nombre:</strong> $nombre</p>
  <p><strong>Email:</strong> $correo</p>
  <p><strong>Teléfono:</strong> $telefono</p>
  <p><strong>Mensaje:</strong> $mensajeC</p>
  <h3>Detalle:</h3>
  <ul>
";


for ($i = 0; $i < count($productos); $i++) {
    $pid  = intval($productos[$i]);
    $cant = intval($cantidades[$i]);

    $s = $conn->prepare("SELECT nombre_producto, precio FROM productos WHERE producto_id = ?");
    $s->bind_param("i", $pid);
    $s->execute();
    $res = $s->get_result();
    $row = $res->fetch_assoc();

    $nombreProd = $row['nombre_producto'];
    $precio     = $row['precio'];
    $subtotal   = $precio * $cant;

    $html .= "<li>$nombreProd — $cant x $" . number_format($precio, 0, ',', '.') . " = $" . number_format($subtotal, 0, ',', '.') . "</li>";
}

$html .= "</ul><p><strong>Total:</strong> $" . number_format($total, 0, ',', '.') . "</p>

<h1> Nos pondremos en contacto contigo enseguida!, gracias por elegir Nachitos para tu cotizacion! </h1>
</body>
</html>";


$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdf_output = $dompdf->output();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $smtp_host;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_pass;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($smtp_user, 'Nachitos');
    $mail->addAddress($correo, $nombre); // Cliente
    $mail->addAddress('admin@nachitos.cl', 'Administrador'); // Admin

    $mail->Subject = 'Comprobante de pedido - Nachitos';
    $mail->Body    = "Hola $nombre,\n\nAdjuntamos tu comprobante de pedido.\n\nSaludos de parte del equipo Nachitos.";
    $mail->addStringAttachment($pdf_output, "Comprobante_Pedido_$pedido_id.pdf");

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar comprobante: {$mail->ErrorInfo}";
}


unset($_SESSION["pedido"]);


echo "<p>¡Gracias por tu pedido! Te enviaremos un correo de confirmación.</p>";
echo "<a href='../pages/catalogo.php'>Volver al catálogo</a>";
