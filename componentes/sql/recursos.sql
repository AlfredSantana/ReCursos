CREATE DATABASE recursos;
USE recursos;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    correo VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    tipo_usuario ENUM('gratis', 'premium') DEFAULT 'gratis',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);
