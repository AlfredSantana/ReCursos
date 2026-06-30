// js/search.js o agrégalo directamente en header-home.php
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-form input[name="q"]');
    const searchBtn = document.querySelector('.search-btn');
    
    // Asegurarse de que el formulario se envíe al hacer clic en la lupa
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto
        
        // Si el campo de búsqueda está vacío, enfocarlo
        if (!searchInput.value.trim()) {
            searchInput.focus();
        } else {
            // Si tiene contenido, enviar el formulario
            searchForm.submit();
        }
    });
    
    // También permitir búsqueda con Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchForm.submit();
        }
    });
    
    // Búsqueda en tiempo real (opcional)
    searchInput.addEventListener('input', function() {
        // Aquí podrías agregar búsqueda en tiempo real si quieres
        console.log('Buscando:', this.value);
    });
});