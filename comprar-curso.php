<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$curso_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$curso_id) {
    header('Location: explorar.php');
    exit;
}

// Obtener información del curso y verificar descuento premium
$query_curso = "SELECT * FROM cursos WHERE id = ?";
$stmt_curso = mysqli_prepare($conexion, $query_curso);
mysqli_stmt_bind_param($stmt_curso, "i", $curso_id);
mysqli_stmt_execute($stmt_curso);
$curso = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_curso));

if (!$curso) {
    header('Location: explorar.php');
    exit;
}

// Verificar si es premium
$query_user = "SELECT tipo FROM usuarios WHERE id = ?";
$stmt_user = mysqli_prepare($conexion, $query_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_user));
$es_premium = ($user_data['tipo'] === 'premium');

$precio_original = $curso['precio'];
$precio_final = $precio_original;
$descuento = 0;

if ($es_premium && $precio_original > 0) {
    $descuento = 20;
    $precio_final = $precio_original * 0.8;
}

// Aquí implementarías la lógica de compra/pago
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Comprar Curso — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <section class="container">
                <h1>Comprar Curso</h1>
                <!-- Aquí iría el formulario de compra/pago -->
            </section>
        </main>
    </div>
</body>

</html>