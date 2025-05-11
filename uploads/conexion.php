<?php
$host = "sql113.infinityfree.com";
$usuario = "if0_38886803";
$contrasena = "donNachitos24";
$basededatos = "if0_38886803_nachitos"; 


$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    
}
?>
