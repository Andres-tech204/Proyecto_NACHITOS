<?php
include ('../uploads/conexion.php');

$id_producto = isset($_GET['producto_id']) ? $_GET['producto_id'] : 0;

if ($id_producto == 0) {
    echo "Producto no encontrado.";
    exit;
}
$sql = "SELECT * FROM productos WHERE producto_id= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_producto); 
$stmt->execute();
$resultado = $stmt->get_result();


if ($producto = $resultado->fetch_assoc()) {
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $producto['nombre_producto']; ?> - Nachitos</title>
    <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>
    <?php include ('../includes/header.php'); ?>

    <main class="detalle-producto">
        <h1><?php echo $producto['nombre_producto']; ?></h1>
        <img src="../imagenes/<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre_producto']; ?>" class="img-producto">
        <p><?php echo $producto['descripcion']; ?></p>
        <p><strong>Precio: $<?php echo number_format($producto['precio'], 0, ',', '.'); ?> CLP</strong></p>
        <p>Stock disponible: <?php echo $producto['stock']; ?></p>

        <form action="../pages/pedido.php" method="POST">
            <input type="hidden" name="agregar" value="1">
            <input type="hidden" name="id" value="<?php echo $producto['producto_id']; ?>">
            <input type="hidden" name="nombre" value="<?php echo $producto['nombre_producto']; ?>">
            <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
            <label>Cantidad: <input type="number" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>"></label>
            <button type="submit">Agregar a Cotización</button>
        </form>
    </main>

    <?php include ('../includes/footer.php'); ?>
</body>
</html>

<?php
} else {
    echo "Producto no encontrado.";
}
?>
