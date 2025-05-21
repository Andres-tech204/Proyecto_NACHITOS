<?php
require_once("/nachitos/ajax/conexion.php");

if (isset($_GET['buscar'])) {
  $busqueda = trim($_GET['buscar']);
  $query = "SELECT nombre, id FROM productos WHERE nombre LIKE ? LIMIT 5";
  $stmt = $conn->prepare($query);
  $like = "%$busqueda%";
  $stmt->bind_param("s", $like);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo '<ul class="sugerencias-lista">';
    while ($row = $result->fetch_assoc()) {
      echo '<li><a href="/nachitos/pages/producto.php?id=' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</a></li>';
    }
    echo '</ul>';
  } else {
    echo '<ul class="sugerencias-lista"><li>No se encontraron productos.</li></ul>';
  }
}
?>
