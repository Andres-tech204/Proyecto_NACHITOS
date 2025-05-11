<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

include("../uploads/conexion.php");

if (!isset($_GET['id'])) {
    echo "ID de pedido no proporcionado.";
    exit;
}

$id_pedido = intval($_GET['id']);

// Obtener info del pedido
$sql_pedido = "SELECT * FROM pedidos WHERE id_pedido = $id_pedido";
$resultado_pedido = $conn->query($sql_pedido);

if ($resultado_pedido->num_rows === 0) {
    echo "Pedido no encontrado.";
    exit;
}

$pedido = $resultado_pedido->fetch_assoc();

// Obtener productos del pedido
$sql_detalles = "SELECT dp.*, pr.nombre AS nombre_producto 
                 FROM detalle_pedido dp
                 JOIN productos pr ON dp.id_producto = pr.id_producto
                 WHERE dp.id_pedido = $id_pedido";

$detalles = $conn->query($sql_detalles);

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Detalle del Pedido #<?= $pedido['id_pedido'] ?></h1>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nombre']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($pedido['correo']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono']) ?></p>
    <p><strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?></p>
    <p><strong>Fecha del pedido:</strong> <?= $pedido['fecha_pedido'] ?></p>

    <h2>Productos</h2>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $detalles->fetch_assoc()): 
                $subtotal = $fila['cantidad'] * $fila['precio_unitario'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                    <td><?= $fila['cantidad'] ?></td>
                    <td>$<?= number_format($fila['precio_unitario'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Total del pedido: $<?= number_format($total, 0, ',', '.') ?></h3>

    <p><a href="ver_pedidos.php">← Volver a la lista de pedidos</a></p>
</body>
</html>
