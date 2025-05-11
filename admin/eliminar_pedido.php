<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

include("../uploads/conexion.php");

if(isset($_GET['id'])) {
    $id_pedido = intval($_GET['id']);

    $conn->query("DELETE FROM detalle_pedido WHERE id_pedido = $id_pedido");

    if ($conn->query("DELETE FROM pedidos WHERE id_pedido = $id_pedido")) {
        header("Location: ver_pedidos.php?msg=eliminado");
    } else {
        echo "❌ Error al eliminar el pedido.";
    }
} else {
    echo "ID de pedido no proporcionado.";
}
?>