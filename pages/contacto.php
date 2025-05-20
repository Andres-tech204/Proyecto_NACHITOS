<?php
session_start();

include ('../includes/header.php');
?>

<main style="min-height: 60vh; display: flex; justify-content: center; align-items: center;">
  <form action="/nachitos/pages/enviar_contacto.php" method="post" style="padding: 2rem; border-radius: 10px; max-width: 400px; width: 100%;" class="formulario-contacto">
    <h2 style="text-align:center; margin-bottom: 1rem;">Contáctanos</h2>
    <div style="margin-bottom: 1rem;">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #ccc; background: transparent; color: inherit;">
    </div>
    <div style="margin-bottom: 1rem;">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #ccc; background: transparent; color: inherit;">
    </div>
    <div style="margin-bottom: 1rem;">
      <label for="telefono">Teléfono:</label>
      <input type="tel" id="telefono" name="telefono" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #ccc; background: transparent; color: inherit;">
    </div>
    <div style="margin-bottom: 1rem;">
      <label for="lugar">Lugar de donde proviene:</label>
      <input type="text" id="lugar" name="lugar" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #ccc; background: transparent; color: inherit;">
    </div>
    <div style="margin-bottom: 1rem;">
      <label for="mensaje">Mensaje:</label>
      <textarea id="mensaje" name="mensaje" rows="4" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #ccc; background: transparent; color: inherit;"></textarea>
    </div>
    <button type="submit" class="BotonConfirmacion" style="width:100%;">Enviar</button>
  </form>
</main>

<?php
include ('../includes/footer.php');
?>