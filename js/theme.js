// theme.js - Versión corregida
const html = document.documentElement;

// Guardar y cargar tema
function loadTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
}

function toggleTheme() {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const themeBtn = document.querySelector('.theme-btn');
    if (themeBtn) {
        const icon = themeBtn.querySelector('img');
        if (icon) {
            if (theme === 'dark') {
                icon.src = "assets/icons/sun.svg"; // Sol para modo oscuro
            } else {
                icon.src = "assets/icons/moon.svg"; // Luna para modo claro
            }
        }
    }
}

// Inicializar tema al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadTheme();
    
    // Configurar botón de tema
    const themeBtn = document.querySelector('.theme-btn');
    if (themeBtn) {
        themeBtn.addEventListener('click', toggleTheme);
    }
});

