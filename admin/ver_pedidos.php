<?php
include 'verificar_admin.php';

include('../uploads/conexion.php');

$sql = "SELECT pedido_id, fecha_pedido, estado, total, descripcion_cliente
        FROM pedidos
        ORDER BY fecha_pedido DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Pedidos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Listado de Pedidos</h1>
        <nav>
            <ul>
                <li><a href="admin.php">Página Principal</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Nombre Cliente</th>
                    <th>Fecha Pedido</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['pedido_id'] . "</td>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['fecha_pedido'] . "</td>";
                        echo "<td>" . $row['estado'] . "</td>";
                        echo "<td>" . number_format($row['total'], 2) . "</td>";
                        echo "<td><pre><?= htmlspecialchars($row['descripcion_cliente']) ?></pre></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay pedidos registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>

<?php
mysqli_close($conn);
?>
