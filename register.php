<?php
include "conexion.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $pass = MD5($_POST['pass']);

    $query = "INSERT INTO usuarios (nombre, correo, pass)
              VALUES ('$nombre', '$correo', '$pass')";

    if (mysqli_query($conexion, $query)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conexion);
    }
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
        <h2>Crear cuenta</h2>

        <?php if (!empty($error))
            echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <button type="submit">Registrarme</button>
        </form>
    </div>

    <?php include "componentes/footer.php"; ?>

</body>

</html>