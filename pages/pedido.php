<?php
session_start();

include('../uploads/conexion.php');
include('../includes/header.php');

if (!isset($_SESSION["pedido"])) {
    $_SESSION["pedido"] = [];
}

$productos = [];
$sql = "SELECT id_producto, nombre, precio FROM productos";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {
    $id = intval($_POST["id"]);
    $cantidad = intval($_POST["cantidad"]);

    if ($cantidad < 1) {
        header("Location: pedido.php?error=cantidad");
        exit;
    }

    $stmt = $conn->prepare("SELECT nombre, precio FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        $nombre = $producto["nombre"];
        $precio = $producto["precio"];

        $existe = false; 
        foreach ($_SESSION["pedido"] as &$item) {
            if ($item["id"] == $id) {
                $item["cantidad"] += $cantidad;
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $_SESSION["pedido"][] = [
                "id" => $id,
                "nombre" => $nombre,
                "precio" => $precio,
                "cantidad" => $cantidad
            ]; 
        }

        header("Location: pedido.php");
        exit;
    } else {
        header("Location: pedido.php?error=producto");
    }
}

if (isset($_GET["eliminar"])) {
    $idEliminar = $_GET["eliminar"];
    $_SESSION["pedido"] = array_filter($_SESSION["pedido"], function ($item) use ($idEliminar) {
        return $item["id"] != $idEliminar; 
    });
    header("Location: pedido.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido - Nachitos</title>
</head>
<body>
    <h1>Realiza tu Pedido</h1>
    <a href="catalogo.php" class="botonEspecial">Continuar Cotizacion</a>
    <h2>Productos</h2>
    <div>
    
        <?php foreach ($productos as $producto): ?>
            <form method="POST" action="pedido.php">
                <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">
                <label><?php echo $producto['nombre']; ?> - $<?php echo number_format($producto['precio'], 0, ',', '.'); ?></label><br>
                Cantidad: <input type="number" name="cantidad" value="1" min="1" required>
                <button type="submit" name="agregar">Agregar al pedido</button>
            </form>
        <?php endforeach; ?>
    </div>

    <h2>Resumen del Pedido</h2>
    <?php if (count($_SESSION["pedido"]) > 0): ?>
        <ul>
            <?php foreach ($_SESSION["pedido"] as $item): ?>
                <li>
                    <?php echo $item["nombre"]; ?> - $<?php echo number_format($item["precio"], 0, ',', '.'); ?> x <?php echo $item["cantidad"]; ?>
                    <a href="pedido.php?eliminar=<?php echo $item["id"]; ?>">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total: $
            <?php
            $total = 0;
            foreach ($_SESSION["pedido"] as $item) {
                $total += $item["precio"] * $item["cantidad"];
            }
            echo number_format($total, 0, ',', '.');
            ?>
        </strong></p>

        <a href="pedir.php">Finalizar Pedido</a>
    <?php else: ?>
        <p>No hay productos en tu pedido.</p>
    <?php endif; ?>

    <?php if (isset($_GET["error"])): ?>
        <p style="color: red;">
            <?php 
            if ($_GET["error"] === "producto") echo "El producto seleccionado no es válido.";
            if ($_GET["error"] === "cantidad") echo "La cantidad ingresada debe ser positiva.";
            ?>
        </p>
    <?php endif; ?>

    <?php include('../includes/footer.php') ?>
</body>
</html>