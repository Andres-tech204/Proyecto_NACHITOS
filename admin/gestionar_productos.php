<?php
require_once '../uploads/conexion.php';
require_once 'verificar_admin.php';

$where = [];

if (!empty($_GET['buscar'])) {
    $buscar = mysqli_real_escape_string($conn, $_GET['buscar']);
    $where[] = "nombre_producto LIKE '%$buscar%'";
}

if (!empty($_GET['categoria'])) {
    $categoria = intval($_GET['categoria']);
    $where[] = "categoria_id = $categoria";
}

$condicion = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT p.*, c.nombre_categoria 
        FROM productos p 
        LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
        $condicion 
        ORDER BY p.producto_id DESC";

$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Productos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<?php
$sql_categorias = "SELECT categoria_id, nombre_categoria FROM categorias";
$res_categorias = mysqli_query($conn, $sql_categorias);
?>

<form method="GET" class="formulario-busqueda">
  <input type="text" name="buscar" placeholder="Buscar por nombre..." value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
  
  <select name="categoria">
    <option value="">-- Todas las categorías --</option>
    <?php while ($cat = mysqli_fetch_assoc($res_categorias)): ?>
      <option value="<?= $cat['categoria_id'] ?>" <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['categoria_id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($cat['nombre_categoria']) ?>
      </option>
    <?php endwhile; ?>
  </select>
  
  <button type="submit">Filtrar</button>
</form>


<div class="contenedor">
    <h2>Gestion de Productos</h2>
    <a href="crear_producto.php" class="boton">Agregar nuevo producto</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)) : ?>
                <tr>
                    <td><?= $row['producto_id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                    <td>$<?= number_format($row['precio'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_categoria']) ?></td>
                    <td>
                        <?php if (!empty($row['imagen_url'])): ?>
                            <img src="../imagenes/<?= htmlspecialchars($row['imagen_url']) ?>" width="80">
                        <?php else: ?>
                            Sin Imagen
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="editar_producto.php?id=<?= $row['producto_id'] ?>">Editar</a> |
                        <a href="eliminar_producto.php?id=<?= $row['producto_id'] ?>" onclick="return confirm('¿Eliminar este proudcto?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>