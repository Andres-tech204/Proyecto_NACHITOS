<?php
session_start();
include('../uploads/conexion.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION["pedido"])) {
    $_SESSION["pedido"] = [];
}

$productos = [];
$sql = "SELECT producto_id, nombre_producto, precio FROM productos";  
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {
    $id = intval($_POST["id"] ?? $_POST["producto_id"] ?? 0);
    $cantidad = intval($_POST["cantidad"] ?? 1);

    if ($cantidad < 1 || $id <= 0) {
        header("Location: pedido.php?error=cantidad");
        exit;
    }

    agregarProductoAPedido($conn, $id, $cantidad);
}


if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["producto_id"])) {
    $id = intval($_GET["producto_id"]);
    if ($id > 0) {
        agregarProductoAPedido($conn, $id, 1); 
    }
}

if (isset($_GET["eliminar"])) {
    $idEliminar = intval($_GET["eliminar"]);
    $_SESSION["pedido"] = array_filter($_SESSION["pedido"], function ($item) use ($idEliminar) {
        return $item["producto_id"] != $idEliminar;  
    });
    $_SESSION["pedido"] = array_values($_SESSION["pedido"]);  
    header("Location: pedido.php");
    exit;
}


function agregarProductoAPedido($conn, $id, $cantidad) {
    $stmt = $conn->prepare("SELECT nombre_producto, precio FROM productos WHERE producto_id = ?");  
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        $nombre_producto = $producto["nombre_producto"];
        $precio = $producto["precio"];

        $existe = false;
        foreach ($_SESSION["pedido"] as &$item) {
            if ($item["producto_id"] == $id) {  
                $item["cantidad"] += $cantidad;
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $_SESSION["pedido"][] = [
                "producto_id" => $id,  
                "nombre_producto" => $nombre_producto,
                "precio" => $precio,
                "cantidad" => $cantidad
            ];
        }

        header("Location: pedido.php");
        exit;
    } else {
        header("Location: pedido.php?error=producto");
        exit;
    }
}

include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido - Nachitos</title>
    <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>
    <h1>Realiza tu Pedido</h1>
    <a href="catalogo.php" class="botonEspecial">Continuar Cotización</a>

    <h2>Productos</h2>
    <div>
        <?php foreach ($productos as $producto): ?>
            <form method="POST" action="pedido.php">
                <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>"> 
                <label><?php echo $producto['nombre_producto']; ?> - $<?php echo number_format($producto['precio'], 0, ',', '.'); ?></label><br>
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
                    <?= htmlspecialchars($item["nombre_producto"]) ?> - 
                    $<?= number_format($item["precio"], 0, ',', '.') ?> x <?= $item["cantidad"] ?>
                    <a href="pedido.php?eliminar=<?= $item["producto_id"] ?>" class="botonEliminar">🗑️ Eliminar</a>  <!-- Cambié "id" por "producto_id" -->
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

        <a href="pedir.php" class="botonFinalizar">Finalizar Pedido</a>
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
