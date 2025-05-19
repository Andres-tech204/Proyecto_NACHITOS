<?php 
session_start(); 
include('includes/header.php');
?>

<header> 
  <p>Calidad Nacional con Calidez Familiar</p>
</header>

<main>
  <section class="producto-destacado">
    <img src="imagenes/HornoDestacado.jpg" alt="Horno de Barro Nachitos" class="img-grande">
  </section>
</main>

<script>
  document.querySelectorAll('.zoomable').forEach(img => {
    img.addEventListener('click', () => {
      const overlay = document.createElement('div');
      overlay.classList.add('zoom-overlay');

      const zoomedImg = document.createElement('img');
      zoomedImg.src = img.src;

      overlay.appendChild(zoomedImg);
      document.body.appendChild(overlay);

      overlay.addEventListener('click', () => {
        overlay.remove();
      });
    });
  });
</script>

<?php include("includes/footer.php"); ?>
