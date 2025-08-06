<?php
// partials/header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Imperio Comercial</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Estilos personalizados con paleta azul -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-chart-line"></i> CRM Ventas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    
                    <!-- =================================================================== -->
                    <!-- == INICIO DEL CAMBIO: Mostrar enlace a Vendedores Y Supervisores == -->
                    <!-- =================================================================== -->
                    <?php if ($_SESSION['user_rol'] === 'vendedor' || $_SESSION['user_rol'] === 'supervisor'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="create_form.php"><i class="fas fa-plus-circle"></i> Cargar Formulario</a>
                        </li>
                    <?php endif; ?>
                    <!-- =================================================================== -->
                    <!-- == FIN DEL CAMBIO == -->
                    <!-- =================================================================== -->

                    <li class="nav-item">
                        <a class="nav-link" href="login.php?logout=true"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4">
