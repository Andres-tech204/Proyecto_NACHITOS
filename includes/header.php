<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nachitos</title>
  <link rel="stylesheet" href="/nachitos/css/estilos.css">
</head>

<body>

<div class="nav-container">
  <nav class="nav-bar">
    <ul class="nav-left">
      <li><a href="/nachitos/pages/catalogo.php">Catálogo</a></li>
      <li><a href="/nachitos/pages/pedir.php">Pedidos</a></li>
    </ul>
    <div class="logo-center">
      <a href="/nachitos/index.php">
        <img src="/nachitos/imagenes/LogoNachitos.png" alt="Logo Nachitos">
      </a>
    </div>
    <form action="/nachitos/pages/catalogo.php" method="GET" class="buscador-global">
      <input type="text" name="buscar" placeholder="Buscar productos..." required>
      <button type="submit">🔍</button>
    </form>
    <ul class="nav-right">
      <li><a href="/nachitos/pages/contacto.php">Contáctanos</a></li>
      <li><a href="/nachitos/pages/aboutUs.php">Sobre Nosotros</a></li>
    </ul>
  </nav>
</div>

<button id="toggle-dark" style="cursor: pointer; background-color: #f86e40; color: white; padding: 10px; border-radius: 5px;">
  🌓 Modo oscuro
</button>
<script>
  document.getElementById('toggle-dark').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
  });
</script>
<script>
  document.getElementById('buscar').addEventListener('keyup', function() {
    const consulta = this.value;

    if (consulta.length > 1) {
      fetch('/nachitos/ajax/buscador.php?buscar=' + encodeURIComponent(consulta))
        .then(res => res.text())
        .then(data => {
          document.getElementById('resultado-busqueda').innerHTML = data;
          document.getElementById('resultado-busqueda').style.display = 'block';
        });
    } else {
      document.getElementById('sugerencias').innerHTML = '';
      document.getElementById('sugerencias').style.display = 'none';
    }
  });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.buscador-global')) {
      document.getElementById('resultado-busqueda').style.display = 'none';
    }
  });
</script>