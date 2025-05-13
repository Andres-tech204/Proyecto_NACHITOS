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
    $nombre = $row['nombre_producto'];
    $precio = $row['precio'];
    $subtotal = $precio * $cant;

    $cuerpoCli .= "- $nombre: $cant x $" . number_format($precio, 0, ',', '.') . " = $" . number_format($subtotal, 0, ',', '.') . "\n";

   
    $total += $subtotal;

    
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
enviarCorreo($correo, "Confirmacion de tu pedido en Nachitos", $cuerpoCli);
enviarCorreo("admin@nachitos.cl", "Nuevo pedido #$pedido_id", $cuerpoCli);

unset($_SESSION["pedido"]);


echo "<p>¡Gracias por tu pedido! Te enviaremos un correo de confirmación.</p>";
echo "<a href='../pages/catalogo.php'>Volver al catálogo</a>";
