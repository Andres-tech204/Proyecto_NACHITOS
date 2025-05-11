<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

include("../uploads/conexion.php");

// Consulta de pedidos
$sql = "SELECT p.id_pedido, p.nombre, p.correo, p.telefono, p.fecha_pedido, p.estado
        FROM pedidos p
        ORDER BY p.fecha_pedido DESC";

$resultado = $conn->query($sql);

if (isset($_GET['msg']) && $_GET['msg'] === 'eliminado') {
    echo "<p style='color: green;'>✅ Pedido eliminado correctamente.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Pedidos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Pedidos Recibidos</h1>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pedido = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $pedido['id_pedido'] ?></td>
                    <td><?= htmlspecialchars($pedido['nombre']) ?></td>
                    <td><?= htmlspecialchars($pedido['correo']) ?></td>
                    <td><?= htmlspecialchars($pedido['telefono']) ?></td>
                    <td><?= $pedido['fecha_pedido'] ?></td>
                    <td><?= ucfirst($pedido['estado']) ?></td>
                    <td><a href="detalle_pedido.php?id=<?= $pedido['id_pedido'] ?>">Ver</a></td>
                    <td>
                        <a href="eliminar_pedido.php?id=<?= $pedido['id_pedido'] ?>"
                          onclick="return confirm('¿Estas seguro de eliminar este pedido?');">Eliminar</a>
                    <td>
                </tr>
            <?php endwhile; ?>
            
        </tbody>
    </table>
</body>
</html>
