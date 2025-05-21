<?php
require_once 'verificar_admin.php';
?>

<h2>Enviar anuncio</h2>
<form method="POST" action="procesar_anuncio.php">
  <input type="text" name="titulo" placeholder="Título del anuncio" required><br><br>
  <textarea name="mensaje" placeholder="Escribe tu anuncio..." rows="6" required></textarea><br>
  <button type="submit">Enviar a todos los clientes con pedidos</button>
</form>
