// js/menu.js - Actualizado con búsqueda expandible
document.addEventListener('DOMContentLoaded', function() {
    console.log('Menu.js cargado - configurando funcionalidades...');
    
    // 1. Menú lateral hamburguesa
    const menuToggle = document.getElementById('menu-toggle');
    const sideMenu = document.getElementById('side-menu');
    const sideMenuOverlay = document.getElementById('side-menu-overlay');
    const closeMenu = document.getElementById('close-menu');
    
    if (menuToggle && sideMenu && sideMenuOverlay && closeMenu) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sideMenu.classList.add('active');
            sideMenuOverlay.classList.add('active');
        });
        
        function closeSideMenu() {
            sideMenu.classList.remove('active');
            sideMenuOverlay.classList.remove('active');
        }
        
        closeMenu.addEventListener('click', closeSideMenu);
        sideMenuOverlay.addEventListener('click', closeSideMenu);
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sideMenu.classList.contains('active')) {
                closeSideMenu();
            }
        });
    }

    // 2. Menú desplegable del perfil (funcional en móvil)
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (userDropdown) {
        const dropdownContent = userDropdown.querySelector('.dropdown-content');
        
        // Para desktop - hover
        if (window.innerWidth > 768) {
            userDropdown.addEventListener('mouseenter', function() {
                dropdownContent.style.display = 'block';
            });
            
            userDropdown.addEventListener('mouseleave', function() {
                dropdownContent.style.display = 'none';
            });
        }
        
        // Para móvil - click/tap
        userDropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                e.stopPropagation();
                
                // Cerrar otros dropdowns
                document.querySelectorAll('.user-dropdown.active').forEach(dropdown => {
                    if (dropdown !== userDropdown) {
                        dropdown.classList.remove('active');
                    }
                });
                
                // Alternar este dropdown
                userDropdown.classList.toggle('active');
            }
        });
        
        // Cerrar al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });
        
        // Cerrar al seleccionar una opción
        if (dropdownContent) {
            dropdownContent.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    userDropdown.classList.remove('active');
                }
            });
        }
    }

    // 3. Búsqueda expandible en móvil
    const searchBar = document.querySelector('.search-bar');
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    
    if (searchBar && searchInput && searchBtn && window.innerWidth <= 768) {
        // Inicializar como colapsado
        searchBar.classList.add('collapsed');
        
        // Expandir al hacer click en el botón de búsqueda
        searchBtn.addEventListener('click', function(e) {
            if (searchBar.classList.contains('collapsed')) {
                e.preventDefault();
                searchBar.classList.remove('collapsed');
                searchInput.focus();
            }
        });
        
        // Colapsar al perder el foco (si está vacío)
        searchInput.addEventListener('blur', function() {
            if (!this.value.trim() && !searchBar.classList.contains('collapsed')) {
                setTimeout(() => {
                    searchBar.classList.add('collapsed');
                }, 200);
            }
        });
        
        // Permitir búsqueda normal cuando está expandido
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !searchBar.classList.contains('collapsed')) {
                // La búsqueda se envía normalmente
            }
        });
    }

    console.log('Todas las funcionalidades móviles configuradas');
});

// User dropdown para móvil
document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.querySelector('.user-dropdown');
    const dropdownOverlay = document.getElementById('dropdown-overlay');
    const dropdownContent = document.querySelector('.user-dropdown .dropdown-content');
    
    if (userDropdown && dropdownOverlay) {
        // Abrir dropdown
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
            dropdownOverlay.classList.toggle('active');
        });
        
        // Cerrar dropdown al hacer tap en el overlay
        dropdownOverlay.addEventListener('click', function() {
            userDropdown.classList.remove('active');
            dropdownOverlay.classList.remove('active');
        });
        
        // Cerrar dropdown al hacer tap en un enlace
        const dropdownLinks = dropdownContent.querySelectorAll('a');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', function() {
                userDropdown.classList.remove('active');
                dropdownOverlay.classList.remove('active');
            });
        });
        
        // Cerrar dropdown al hacer scroll
        window.addEventListener('scroll', function() {
            userDropdown.classList.remove('active');
            dropdownOverlay.classList.remove('active');
        });
    }
    
    // Cerrar dropdown cuando se hace tap fuera (para desktop también)
    document.addEventListener('click', function(e) {
        if (!userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
            if (dropdownOverlay) {
                dropdownOverlay.classList.remove('active');
            }
        }
    });
});