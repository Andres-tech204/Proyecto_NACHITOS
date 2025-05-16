<?php
require_once '../uploads/conexion.php';
require_once 'verificar_admin.php';

if (!isset($_GET['id'])) {
    die('ID de producto no especificado.');
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM productos WHERE producto_id = $id";
$resultado = mysqli_query($conexion, $sql);
$producto = mysqli_fetch_assoc($resultado);

if (!$producto) {
    die('Producto no encontrado.');
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    $errores = [];

    $imagen_actual = $producto['imagen_url'];
    $imagen_nueva = $imagen_actual;

    if (empty($nombre) || empty($descripcion)) {
        $errores[] = "Nombre y descripción son obligatorios.";
    }

    if ($precio <= 0) {
        $errores[] = "El precio debe ser mayor a 0.";
    }

    if ($stock < 1) {
        $errores[] = "El stock debe ser al menos 1.";
    }

    $sql_verificar = "SELECT COUNT(*) AS total FROM productos WHERE nombre_producto = '$nombre' AND producto_id != $id";
    $res_verificar = mysqli_query($conexion, $sql_verificar);
    $total = mysqli_fetch_assoc($res_verificar)['total'];

    if ($total > 0) {
        $errores[] = "Ya existe otro producto con ese nombre.";
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

        if (count($errores) === 0) {
            $ruta_destino = '../imagenes/' . $imagen_nombre;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                $imagen_nueva = $imagen_nombre;
            } else {
                $errores[] = "Error al subir la imagen.";
            }
        }
    }

    if (count($errores) === 0) {
        $sql_update = "UPDATE productos SET 
                       nombre_producto = '$nombre',
                       descripcion = '$descripcion',
                       precio = $precio,
                       stock = $stock,
                       imagen_url = '$imagen_nueva'
                       WHERE producto_id = $id";

        if (mysqli_query($conexion, $sql_update)) {
            header("Location: gestionar_productos.php?actualizado=1");
            exit;
        } else {
            $mensaje = "Error al actualizar el producto.";
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
  <title>Editar Producto</title>
  <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="contenedor">
  <h2>Editar producto</h2>

  <?php if (!empty($mensaje)) : ?>
    <p style="color:red;"><?= htmlspecialchars($mensaje) ?></p>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre_producto']) ?>" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4" required><?= htmlspecialchars($producto['descripcion']) ?></textarea><br><br>

    <label>Precio:</label><br>
    <input type="number" name="precio" step="0.01" value="<?= $producto['precio'] ?>" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" value="<?= $producto['stock'] ?>" required><br><br>

    <label>Imagen actual:</label><br>
    <?php if ($producto['imagen_url']) : ?>
      <img src="../imagenes/<?= htmlspecialchars($producto['imagen_url']) ?>" width="120"><br>
    <?php endif; ?>

    <label>¿Deseas cambiar la imagen?</label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <input type="submit" value="Guardar cambios" class="boton">
    <a href="gestionar_productos.php" class="boton">Cancelar</a>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
