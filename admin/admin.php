<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
</head>
<body>
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> (Administrador)</h1>

    <ul>
        <li><a href="gestionar_productos.php">Gestionar Productos</a></li>
        <li><a href="ver_pedidos.php">Ver Pedidos</a></li>
    </ul>

    <a href="../login/logout.php">Cerrar sesión</a>
</body>
</html>
