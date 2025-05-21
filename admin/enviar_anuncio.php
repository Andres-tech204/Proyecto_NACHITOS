<?php
include '../uploads/conexion.php';
include '../uploads/mail_config.php';
require_once 'verificar_admin.php';
// Verificar si el usuario tiene permisos de administrador

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $mensaje = $_POST['mensaje'];

    // Obtener correos válidos
    $sql = "SELECT DISTINCT cs.correo
            FROM correos_suscritos cs
            JOIN pedidos p ON LOWER(cs.correo) = LOWER(p.descripcion_cliente)";

    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while ($fila = $result->fetch_assoc()) {
            $correo = $fila['correo'];
            enviarCorreo($correo, $titulo, $mensaje); // Usa tu función configurada
        }
        echo "Anuncio enviado a todos los clientes registrados con pedido.";
    } else {
        echo "No hay correos válidos con pedidos.";
    }
}
?>
