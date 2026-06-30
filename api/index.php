<?php
// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Eliminar la barra inicial
$path = ltrim($path, '/');

// Si es la raíz o está vacío, carga index.php
if (empty($path) || $path == '') {
    require __DIR__ . '/../index.php';
    exit;
}

// Mapeo de rutas a archivos PHP
$routes = [
    'login' => 'login.php',
    'registro' => 'registro.php',
    'dashboard' => 'dashboard.php',
    'explorar' => 'explorar.php',
    'perfil' => 'perfil.php',
    'crear-curso' => 'crear-curso.php',
    'ver-curso' => 'ver-curso.php',
    'comprar-curso' => 'comprar-curso.php',
    // AÑADE AQUÍ TODAS TUS PÁGINAS
];

// Si la ruta existe en el mapeo, carga ese archivo
if (isset($routes[$path])) {
    require __DIR__ . '/../' . $routes[$path];
    exit;
}

// Si la ruta termina en .php, intenta cargarla
if (strpos($path, '.php') !== false) {
    $file = __DIR__ . '/../' . $path;
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// Si nada coincide, carga index.php
require __DIR__ . '/../index.php';
?>