<?php
require_once '../uploads/conexion.php';
require_once 'verificar_admin.php';

$mensaje = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    $errores = [];

    if (empty($nombre) || empty($descripcion)) {
        $errores[] = "Nombre y descripción son obligatorios.";
    }

    if ($precio <= 0) {
        $errores[] = "El precio debe ser mayor a 0.";
    }

    if ($stock < 1) {
        $errores[] = "El stock debe ser al menos 1.";
    }

    $sql_verificar = "SELECT COUNT(*) AS total FROM productos WHERE nombre_producto = '$nombre'";
    $res_verificar = mysqli_query($conn, $sql_verificar);
    $total = mysqli_fetch_assoc($res_verificar)['total'];

    if ($total > 0) {
        $errores[] = "Ya existe un producto con ese nombre.";
    }

    if (!empty($_FILES['imagen']['name'])) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $extension = strtolower(pathinfo($imagen_nombre, PATHINFO_EXTENSION));
        $peso = $_FILES['imagen']['size'];
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extension, $permitidas)) {
            $errores[] = "Formato de imagen no permitido.";
        }

        if ($peso > 2 * 1024 * 1024) {
            $errores[] = "La imagen no debe pesar más de 2 MB.";
        }
    } else {
        $errores[] = "Debes seleccionar una imagen.";
    }

    if (count($errores) === 0) {
        $ruta_imagen = '../imagenes/' . $imagen_nombre;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
            $sql = "INSERT INTO productos (nombre_producto, descripcion, precio, stock, imagen_url)
                    VALUES ('$nombre', '$descripcion', $precio, $stock, '$imagen_nombre')";
            if (mysqli_query($conn, $sql)) {
                header("Location: gestionar_productos.php?exito=1");
                exit;
            } else {
                $mensaje = "Error al guardar en la base de datos.";
            }
        } else {
            $mensaje = "Error al subir la imagen.";
        }
    } else {
        $mensaje = implode("<br>", $errores);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Producto</title>
  <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="contenedor">
  <h2>Crear nuevo producto</h2>

  <?php if (!empty($mensaje)): ?>
    <p style="color:red;"><?= htmlspecialchars($mensaje) ?></p>
  <?php endif; ?>

  <form action="crear_producto.php" method="POST" enctype="multipart/form-data">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4" required></textarea><br><br>

    <label>Precio:</label><br>
    <input type="number" name="precio" step="0.01" min="0" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" min="1" required><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="imagen" accept="image/*" required><br><br>

    <input type="submit" value="Guardar" class="boton">
    <a href="gestionar_productos.php" class="boton">Cancelar</a>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
