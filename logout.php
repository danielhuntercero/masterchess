<?php
// logout.php
session_start();
session_unset();
session_destroy();

// Redirigir al index.php a la sección de login
header("Location: index.php#login-section");
exit();
?>