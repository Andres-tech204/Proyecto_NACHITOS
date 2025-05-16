<?php
require_once '../uploads/conexion.php';
require_once 'verificar_admin.php';

if (!isset($_GET['id'])) {
    die('ID no especificado.');
}

$id = intval($_GET['id']);

$sql = "DELETE FROM productos WHERE producto_id = $id";
if (mysqli_query($conn, $sql)) {
    header("Location: gestionar_productos.php?eliminado=1");
    exit;
} else {
    echo "Error al eliminar el producto.";
}
?>
