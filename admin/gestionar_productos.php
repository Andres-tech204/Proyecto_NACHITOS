<?php
include '../uploads/conexion.php';

$mensaje = '';

include 'verificar_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Productos</title>
</head>
<body>
    <h1>Agregar Nuevo Producto</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Descripcion:</label><br>
        <textarea name= "descripcion" required></textarea></br><br>

        <label>Precio:</label><br>
        <input type="number" name="precio" step="0.01" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>

        <label>Imagen del producto:</label><br>
        <input type="file" name="imagen" accept="imagenes/*" required><br><br>

        <button type="submit">Guardar Producto</button>
    </form>

    <p><?= $mensaje ?></p>
</body>
</html>
