<?php 
session_start();
include '../uploads/conexion.php'; 

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena']; 

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo_electronico = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) { 
        $_SESSION['id'] = $usuario['id_usuario'];
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['perfil'] = $usuario['perfil'];

        if ($_SESSION['perfil'] === 'administrador') {
            header("Location: ../admin/admin.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $mensaje = "Correo o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <form method="post" action="">
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>

        <button type="submit">Entrar</button>
    </form>

    <p><?= $mensaje ?></p>
</body>
</html>

