<?php
include '/conexion.php'; 

if (isset($_POST['correo'])) {
  $correo = strtolower(trim($_POST['correo']));
  
  if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $sql = "INSERT IGNORE INTO correos_suscritos (correo) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->close();
  }
}
header("Location: ../index.php?mensaje=gracias");
