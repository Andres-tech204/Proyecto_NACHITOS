<?php
include '../uploads/conexion.php'; // Conectamos a la base de datos

$mensaje = '';

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar
    $tipo_usuario = 'cliente';

    // Verificamos si el correo ya existe
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "El correo ya está registrado.";
    } else {
        // Insertar nuevo usuario
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $correo, $contraseña, $tipo_usuario);

        if ($stmt->execute()) {
            $mensaje = "¡Registro exitoso!";
        } else {
            $mensaje = "Error al registrar: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Nachitos</title>
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form method="post" action="">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contraseña" required><br><br>

        <button type="submit">Registrarse</button>
    </form>

    <p><?= $mensaje ?></p>
</body>
</html>
