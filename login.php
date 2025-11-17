<?php
include "conexion.php";

$correo = $_POST['correo'];
$pass = MD5($_POST['pass']);

$query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' AND pass='$pass'");

if (mysqli_num_rows($query) > 0) {
    $usuario = mysqli_fetch_assoc($query);
    session_start();
    $_SESSION['usuario'] = $usuario;

    header("Location: index.php");
} else {
    echo "Credenciales inválidas";
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <?php include "componentes/header.php"; ?>

    <div class="form-container">
        <h2>Iniciar sesión</h2>

        <?php if (!empty($error))
            echo "<p style='color:red'>$error</p>"; ?>

        <form method="POST">
            <input class="input" type="email" name="correo" placeholder="Correo" required>
            <input class="input" type="password" name="password" placeholder="Contraseña" required>

            <button class="btn">Entrar</button>
        </form>

        <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
    </div>

</body>

</html>