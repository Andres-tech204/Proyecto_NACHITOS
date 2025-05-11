<?php
include ('../uploads/conexion.php');

$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo - Nachitos</title>
  <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
  <?php include ('../includes/header.php'); ?>
  <main class="catalogo-contenedor">
    <h1>Nuestros Productos</h1>
    <div class="productos-grid">
      <?php while ($producto = $resultado->fetch_assoc()): ?>
        <div class="producto-card">
          <img src="../imagenes/<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>" class="img-producto">
          <h2><?php echo $producto['nombre']; ?></h2>
          <p>$<?php echo number_format($producto['precio'], 0, ',', '.'); ?> CLP</p>
          <a href="../productos/detalle_producto.php?id=<?php echo $producto['id_producto']; ?>" class="boton-reservar">Ver más</a>
          <form action="../pages/pedido.php" method="POST" style="margin-top: 10px;">
            <input type="hidden" name="agregar" value="1">
            <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">
            <input type="hidden" name="nombre" value="<?php echo $producto['nombre']; ?>">
            <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
            <input type="number" name="cantidad" value="1" min="1" style="width: 60px;">
            <button type="submit">Agregar a Cotización</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  </main>
  <?php include ("../includes/footer.php"); ?>
</body>
</html>
