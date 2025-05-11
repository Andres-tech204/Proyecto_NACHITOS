<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: login.php");
    exit;
}

include("../uploads/conexion.php");

$mensaje_reserva = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = isset($_SESSION['id']) ? $_SESSION['id'] : null;

    if ($id_usuario === null) {
        $mensaje_reserva = "⚠️ No se pudo obtener tu ID de usuario. Inicia sesión de nuevo.";
    } else {
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $fecha_reserva = $fecha . ' ' . $hora;
        $mensaje = $_POST['mensaje'];

        $stmt = $conn->prepare("INSERT INTO reservas (id_usuario, fecha_reserva, mensaje, estado) VALUES (?, ?, ?, 'pendiente')");
        $stmt->bind_param("iss", $id_usuario, $fecha_reserva, $mensaje);

        if ($stmt->execute()) {
            $mensaje_reserva = "✅ ¡Reserva enviada correctamente!";
        } else {
            $mensaje_reserva = "❌ Error al guardar la reserva.";
        }

        $stmt->close();
    }
}

?>

<?php include("includes/header.php"); ?>

<main>
    <div class="container">
        <h2>Realizar una Reserva</h2>
        <?php if ($mensaje_reserva): ?>
            <p><?= $mensaje_reserva ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="fecha">Fecha:</label><br>
            <input type="date" name="fecha" required><br><br>

            <label for="hora">Hora:</label><br>
            <input type="time" name="hora" required><br><br>

            <label for="mensaje">Mensaje (opcional):</label><br>
            <textarea name="mensaje" rows="4" cols="40"></textarea><br><br>

            <button type="submit">Reservar</button>
        </form>
    </div>
</main>

<?php include("includes/footer.php"); ?>
