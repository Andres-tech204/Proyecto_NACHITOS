<?php
require_once '../uploads/conexion.php';
require_once 'verificar_admin.php';

$sql = "SELECT * FROM productos";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Productos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="contenedor">
    <h2>Gestion de Productos</h2>
    <a href="crear_producto.php" class="boton">Agregar nuevo producto</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)) : ?>
                <tr>
                    <td><?= $row['producto_id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                    <td>$<?= number_format($row['precio'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td>
                        <?php if (!empty($row['imagen_url'])): ?>
                            <img src="../imagenes/<?= htmlspecialchars($row['imagen_url']) ?>" width="80">
                        <?php else: ?>
                            Sin Imagen
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="editar_producto.php?id=<?= $row['producto_id'] ?>">Editar</a> |
                        <a href="eliminar_producto.php?id=<?= $row['producto_id'] ?>" onclick="return confirm('¿Eliminar este proudcto?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>