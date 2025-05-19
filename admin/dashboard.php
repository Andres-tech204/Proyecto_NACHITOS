<?php
require_once '../uploads/conexion.php';
require_once 'verificar_admin.php';
$query_pedidos_mes = "
    SELECT DATE_FORMAT(fecha_pedido, '%Y-%m') AS mes, COUNT(*) AS cantidad
    FROM pedidos
    WHERE fecha_pedido >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY mes
    ORDER BY mes ASC
";
$res_pedidos_mes = mysqli_query($conn, $query_pedidos_mes);

$meses = [];
$cantidades = [];

while ($row = mysqli_fetch_assoc($res_pedidos_mes)) {
    $meses[] = $row['mes'];
    $cantidades[] = $row['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Nachitos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="contenedor">
    <h2>Panel de Administracion</h2>
    <?php
    $res_productos = mysqli_query($conn, "SELECT COUNT(*) AS total_productos FROM productos");
    $total_productos = mysqli_fetch_assoc($res_productos)['total_productos'];

    $res_pedidos = mysqli_query($conn, "SELECT COUNT(*) AS total_pedidos FROM pedidos");
    $total_pedidos = mysqli_fetch_assoc($res_pedidos)['total_pedidos'];

    $res_clientes = mysqli_query($conn, "SELECT COUNT(DISTINCT correo_cliente) AS total_clientes FROM pedidos");
    $total_clientes = mysqli_fetch_assoc($res_clientes)['total_clientes'];

    $res_recaudado = mysqli_query($conn, "SELECT SUM(total) AS total_recaudado FROM pedidos");
    $total_recaudado = mysqli_fetch_assoc($res_recaudado)['total_recaudado'];
    ?>
    <div class="indicadores">
        <h3 style="margin-top: 40px;">Pedidos por Mes (últimos 6 meses)</h3>
        <canvas id="graficoPedidos" width="400" height="200"></canvas>

        <div class="tarjeta">
            <h3>Total Productos</h3>
            <p><?= $total_productos ?></p>
        </div>
        
        <div class="tarjeta">
            <h3>Total Pedidos</h3>
            <p><?= $total_pedidos ?></p>
        </div>

        <div class="tarjeta">
            <h3>Clientes Unicos</h3>
            <p><?= $total_clientes ?></p>
        </div>

        <div class="tarjeta">
            <h3>Total Recaudado</h3>
            <p>$<?= number_format($total_recaudado, 0, ',', '.') ?></p> 
        </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoPedidos').getContext('2d');

    const graficoPedidos = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($meses) ?>,
            datasets: [{
                label: 'Pedidos',
                data: <?= json_encode($cantidades) ?>,
                backgroundColor: 'rgba(52, 152, 219, 0.5)',
                borderColor: 'rgba(41, 128, 185, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>

</body>
</html>