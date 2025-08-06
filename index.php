<?php
// index.php
require_once 'lib/config.php';

// Si el usuario ya ha iniciado sesión, lo redirige a su dashboard.
// De lo contrario, lo envía a la página de login.
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
