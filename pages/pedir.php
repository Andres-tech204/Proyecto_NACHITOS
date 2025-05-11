<?php
session_start();
include ('../includes/header.php');
$pedido = $_SESSION["pedido"] ?? [];
?>

<h2>Tu Cotización</h2>
<?php if (empty($pedido)): ?>
    <p>No has agregado productos.</p>
<?php else: ?>
    <ul>
        <?php foreach ($pedido as $item): ?>
            <li>
                <?= $item["nombre"] ?> - Cantidad: <?= $item["cantidad"] ?> - $<?= $item["precio"] ?>
                <a href="../pages/pedido.php?eliminar=<?= $item["id"] ?>">Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Formulario de Pedido</h3>
    <form action="../uploads/procesar_pedido.php" method="POST">
        <label>Nombre: <input type="text" name="nombre" required></label><br>
        <label>Correo: <input type="email" name="correo" required></label><br>
        <label>Teléfono: <input type="text" name="telefono" required></label><br>
        <label>Mensaje (opcional): <textarea name="mensaje"></textarea></label><br>
        <button type="submit">Enviar Pedido</button>
    </form>
<?php endif; 
include ('../includes/footer.php');
?>
