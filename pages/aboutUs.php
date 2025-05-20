<?php
session_start();

include ('../includes/header.php');
?>

<main style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 70vh; padding: 2rem;">
  <img src="/nachitos/imagenes/LogoNachitos.png" alt="Nachitos" class="fade-in-img" style="width: 180px; margin-bottom: 1.5rem;">
  <p style="max-width: 500px; text-align: center; font-size: 1.2rem; margin-bottom: 2rem;">
    <strong>Nachitos</strong> es una empresa dedicada a ofrecer los mejores hornos y productos para tu cocina, combinando tradición, calidad y pasión por la gastronomía. Nuestro compromiso es brindarte una experiencia única, con atención personalizada y productos de excelencia.
  </p>
  <a href="/nachitos/pages/catalogo.php" class="btn btn-catalogo">Ver Catálogo</a>
</main>

<style>
.fade-in-img {
  opacity: 0;
  animation: fadeIn 1.5s ease-in forwards;
}
@keyframes fadeIn {
  to { opacity: 1; }
}
.btn-catalogo {
  display: inline-block;
  padding: 0.8rem 2rem;
  background: #f86e40;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: bold;
  text-decoration: none;
  transition: background 0.3s, color 0.3s, transform 0.2s;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  cursor: pointer;
}
.btn-catalogo:hover {
  background: #934c44;
  color: #fff;
  transform: translateY(-2px) scale(1.03);
}
</style>

<?php
include ('../includes/footer.php');
?>