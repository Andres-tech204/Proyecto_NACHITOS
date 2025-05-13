<?php
session_start();
include '../includes/header.php';
include '../uploads/conexion.php';

if (!isset($_SESSION["pedido"]) || count($_SESSION["pedido"]) === 0) {
    echo "<p>No hay productos en el pedido.</p>";
    exit;
}
?>

<h2>Finalizar Pedido</h2>
<form action="../uploads/procesar_pedido.php" method="POST">
    <h3>Resumen del Pedido:</h3>
    <ul>
        <?php 
        $total = 0;
        foreach ($_SESSION["pedido"] as $item): 
            $subtotal = $item["precio"] * $item["cantidad"];
            $total += $subtotal;
        ?>
            <li>
                <?= htmlspecialchars($item["nombre_producto"]) ?> - 
                $<?= number_format($item["precio"], 0, ',', '.') ?> x <?= $item["cantidad"] ?> = 
                $<?= number_format($subtotal, 0, ',', '.') ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Total:</strong> $<?= number_format($total, 0, ',', '.') ?></p>

    <label>Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label>Correo:</label>
    <input type="email" name="correo" required><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono" required><br>

    <label>Mensaje adicional:</label><br>
    <textarea name="mensaje" rows="4" cols="40"></textarea><br>
    <?php foreach($_SESSION['pedido'] as $item): ?>
        <input type="hidden" name="producto_id[]" value="<?= $item['producto_id'] ?>">
        <input type="hidden" name="cantidad[]"    value="<?= $item['cantidad'] ?>">
    <?php endforeach; ?>

    <button type="submit">Enviar Pedido</button>
</form>

<?php include '../includes/footer.php'; ?>
