<?php
$conexion = mysqli_connect("localhost", "root", "", "recursos_db");

if (!$conexion) {
    die("Error al conectar: " . mysqli_connect_error());
}
?>