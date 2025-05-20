<?php
include ('../uploads/conexion.php');

$buscar = isset($_GET['buscar']) ? mysqli_real_escape_string($conn, $_GET['buscar']) : '';

if ($buscar !== '') {
    $sql = "SELECT * FROM productos WHERE nombre_producto LIKE '%$buscar%' OR descripcion LIKE '%$buscar%'";
} else {
    $sql = "SELECT * FROM productos";
}
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo - Nachitos</title>
  <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>
  <?php include ('../includes/header.php'); ?>
  <main class="catalogo-contenedor">
    <?php if ($buscar !== ''): ?>
      <h3>Resultados para "<?= htmlspecialchars($buscar) ?>"</h3>
    <?php endif; ?>

    <h1>Nuestros Productos</h1>
    <div class="productos-grid">
      <?php while ($productos = $resultado->fetch_assoc()): ?>
        <div class="productos-card">
          <a href="../productos/detalle_producto.php?producto_id=<?php echo $productos['producto_id']; ?>">
            <img src="../imagenes/<?php echo $productos['imagen_url']; ?>" alt="<?php echo $productos['nombre_producto']; ?>" class="img-producto">
          </a>
          <h2><?php echo $productos['nombre_producto']; ?></h2>
          <p>$<?php echo number_format($productos['precio'], 0, ',', '.'); ?> CLP</p>
       
          <a href="../productos/detalle_producto.php?producto_id=<?php echo $productos['producto_id']; ?>">Ver Detalles</a>
    
          <form action="../pages/pedido.php" method="POST" style="margin-top: 10px;">
            <input type="hidden" name="agregar" value="1">
            <input type="hidden" name="id" value="<?php echo $productos['producto_id']; ?>">
            <input type="hidden" name="nombre" value="<?php echo $productos['nombre_producto']; ?>">
            <input type="hidden" name="precio" value="<?php echo $productos['precio']; ?>">
            <input type="number" name="cantidad" value="1" min="1" style="width: 60px;">
            <button class="BotonConfirmacion" type="submit">
              <i data-lucide="zap"></i>
              Agregar a Cotización
            </button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  </main>
  <?php include ("../includes/footer.php"); ?>
  <script 
  src="https://unpkg.com/lucide@latest"></script>
  <script src="script.js"></script>
</body>
</html>
