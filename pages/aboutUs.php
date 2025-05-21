<?php
session_start();

include ('../includes/header.php');
?>

<main class="aboutus-main">
  <div class="aboutus-content">
    <img src="/nachitos/imagenes/Imagen_Familiar.jpeg" alt="Nachitos" class="fade-in-img">
    <div class="aboutus-text fade-in-text">
      <p>
        <strong>Nachitos</strong> es una empresa dedicada a ofrecer los mejores hornos y productos para tu cocina, combinando tradición, calidad y pasión por la gastronomía. Nuestro compromiso es brindarte una experiencia única, con atención personalizada y productos de excelencia.
      </p>
      <a href="/nachitos/pages/catalogo.php" class="btn btn-catalogo">Ver Catálogo</a>
    </div>
  </div>
</main>

<style>
.aboutus-main {
  min-height: 70vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}
.aboutus-content {
  display: flex;
  align-items: center;
  gap: 2rem;
  max-width: 900px;
  width: 100%;
}
.fade-in-img {
  width: 3000px;
  border-radius: 12px;
  opacity: 0;
  animation: fadeIn 1.5s ease-in forwards;
}
@keyframes fadeIn {
  to { opacity: 1; }
}
.aboutus-text {
  max-width: 500px;
  text-align: left;
  font-size: 1.2rem;
  opacity: 0;
  transform: translateY(30px);
  animation: fadeSlideIn 1.2s 0.7s cubic-bezier(.23,1.01,.32,1) forwards;
}
@keyframes fadeSlideIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.btn-catalogo {
  display: inline-block;
  margin-top: 1.5rem;
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
/* Responsive */
@media (max-width: 700px) {
  .aboutus-content {
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
  }
  .aboutus-text {
    text-align: center;
  }
  .fade-in-img {
    width: 90vw;
    max-width: 320px;
  }
}
</style>

<?php
include ('../includes/footer.php');
?>