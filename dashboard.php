<?php
// dashboard.php
require_once 'lib/config.php';
require_once 'lib/functions.php';

check_login(); // Asegura que el usuario esté logueado

$user_id = $_SESSION['user_id'];
$user_rol = $_SESSION['user_rol'];
$user_nombre = $_SESSION['user_nombre'];

include 'partials/header.php';
?>

<div class="container-fluid">
    <h1 class="mb-4">Bienvenido, <?= htmlspecialchars($user_nombre) ?></h1>

    <?php
    // Cargar la vista del dashboard según el rol del usuario
    switch ($user_rol) {
        case 'vendedor':
            $stats = get_vendedor_stats($pdo, $user_id);
            $formularios = get_formularios_by_vendedor($pdo, $user_id);
            include 'views/dashboard_vendedor.php';
            break;
        case 'supervisor':
            $formularios = get_all_formularios($pdo);
            include 'views/dashboard_supervisor.php';
            break;
        case 'superusuario':
            $formularios = get_all_formularios($pdo);
            $usuarios = get_all_users($pdo);
            $log_actividad = get_activity_log($pdo);
            include 'views/dashboard_superusuario.php';
            break;
        default:
            echo "<div class='alert alert-danger'>Rol de usuario no reconocido.</div>";
            break;
    }
    ?>
</div>

<?php include 'partials/footer.php'; ?>
