<?php
// componentes/sidebar-secciones.php
// Verificar si el usuario es premium
$es_premium = ($_SESSION['user_type'] ?? 'normal') === 'premium';
?>

<div class="sidebar-section">

    <?php if (!$es_premium): ?>
        <div class="premium-promo">
            <div class="premium-content">
                <h4>Obtener Premium</h4>
                <p>Accede a cursos exclusivos y sin anuncios</p>
                <a href="suscripcion.php" class="btn-premium">Probar Premium</a>
            </div>

            <!-- Anuncio GIF con rotación controlada -->
            <div class="ad-container">
                <img src="assets/ads/tenis.gif" alt="Anuncio" class="ad-gif" id="smart-rotating-ad">
                <span class="ad-label">Publicidad</span>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const adElement = document.getElementById('smart-rotating-ad');
                const gifs = [
                    { src: 'assets/ads/tenis.gif', repetitions: 3, duration: 5000 },
                    { src: 'assets/ads/html5.gif', repetitions: 2, duration: 7000 },
                    { src: 'assets/ads/helado.gif', repetitions: 4, duration: 4000 },
                    { src: 'assets/ads/black_friday.gif', repetitions: 3, duration: 6000 }
                ];

                let currentGifIndex = 0;
                let currentRepetition = 0;

                function rotateSmartAd() {
                    const currentGif = gifs[currentGifIndex];

                    if (currentRepetition < currentGif.repetitions - 1) {
                        // Mismo GIF, siguiente repetición
                        currentRepetition++;
                        adElement.src = currentGif.src + '?t=' + Date.now(); // Forzar recarga
                    } else {
                        // Cambiar al siguiente GIF
                        currentRepetition = 0;
                        currentGifIndex = (currentGifIndex + 1) % gifs.length;
                        adElement.src = gifs[currentGifIndex].src;
                    }
                }

                // Usar la duración del GIF actual para el intervalo
                function startRotation() {
                    const currentGif = gifs[currentGifIndex];
                    setInterval(rotateSmartAd, currentGif.duration);
                }

                startRotation();
            });
        </script>
    <?php endif; ?>
</div>