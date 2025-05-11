<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php'; 

$nombre = "Administrador";
$correo = "administrador@nachitos.cl";
$contrasena_plana = "admin123"; 
$contrasena_hash = password_hash($contrasena_plana, PASSWORD_DEFAULT);
$tipo_usuario = "admin";

$sql = "INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $correo, $contrasena_hash, $tipo_usuario);

if ($stmt->execute()) {
    echo "✅ Usuario administrador creado correctamente.";
} else {
    echo "❌ Error: " . $stmt->error;
}

$conn->close();
?>
