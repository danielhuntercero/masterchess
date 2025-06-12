<?php
// config/db.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Usuario por defecto de XAMPP para MySQL
define('DB_PASSWORD', 'Canarias2025');      // Contraseña por defecto de XAMPP para MySQL (vacía)
define('DB_NAME', 'masterchess'); // Nombre de tu base de datos

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conectado a la base de datos correctamente!"; // Descomentar para probar la conexión
} catch (PDOException $e) {
    die("Error conectando a la base de datos: " . $e->getMessage());
}
?>