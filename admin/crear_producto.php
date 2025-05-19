<?php
require_once '../uploads/conexion.php';
require_once 'verificar_admin.php';

$mensaje = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $categoria_id = intval($_POST['categoria_id']);


    $errores = [];
    if ($categoria_id < 1) {
    $errores[] = "Debes seleccionar una categoría válida.";
    }


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
        $imagen_nombre = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['imagen']['name']));
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
            $sql = "INSERT INTO productos (nombre_producto, descripcion, precio, stock, imagen_url, categoria_id)
                    VALUES ('$nombre', '$descripcion', $precio, $stock, '$imagen_nombre', $categoria_id)";
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
  <?php
        $sql_cat = "SELECT categoria_id, nombre_categoria FROM categorias";
        $res_cat = mysqli_query($conn, $sql_cat);
    ?>


  <form action="crear_producto.php" method="POST" enctype="multipart/form-data">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4" required></textarea><br><br>

    <label>Precio:</label><br>
    <input type="number" name="precio" step="0.01" min="0" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" min="1" required><br><br>
    <label>Categoría:</label><br>
    <select name="categoria_id" required>
        <option value="">-- Selecciona una categoría --</option>
        <?php while($fila = mysqli_fetch_assoc($res_cat)): ?>
            <option value="<?= $fila['categoria_id'] ?>"><?= htmlspecialchars($fila['nombre_categoria']) ?></option>
        <?php endwhile; ?>
    </select><br><br>


    <label>Imagen:</label><br>
    <input type="file" name="imagen" accept="image/*" required><br><br>

    <input type="submit" value="Guardar" class="boton">
    <a href="gestionar_productos.php" class="boton">Cancelar</a>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
