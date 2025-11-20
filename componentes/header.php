<header class="site-header index-header">
    <div class="wrap">

        <!-- Logo -->
        <a href="<?php echo isset($_SESSION['user_id']) ? 'home.php' : 'index.php'; ?>" class="logo-wrap">
            <img title="ReCursos logo" src="assets/logo/logo3sf.png" class="logo" alt="ReCursos logo">
            <div class="logo-text">
                <span class="logo-re">Re</span><span class="logo-cursos">Cursos</span>
            </div>
        </a>


        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <form method="GET" action="buscar.php" class="search-form">
                <input type="text" name="q" placeholder="Buscar cursos..."
                    value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" class="search-input">
                <button type="submit" class="search-btn">
                    <img src="assets/icons/search.svg" class="search-icon" alt="Buscar">
                </button>
            </form>
        </div>

        <!-- Navegación -->
        <nav class="nav">
            <ul>
                <li><a class="btn-login" href="login.php">Iniciar Sesión</a></li>
                <li><a class="btn-sm" href="register.php">Regístrate</a></li>
                <li>
                    <button class="theme-btn" id="theme-toggle">
                        <img id="theme-icon" src="assets/icons/moon.svg" alt="modo oscuro">
                    </button>
                </li>
            </ul>
        </nav>

    </div>
</header>