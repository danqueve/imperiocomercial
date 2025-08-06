<?php
// login.php
require_once 'lib/config.php';
require_once 'lib/functions.php';

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    redirect('login.php');
}

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'] ?? '';
    $clave = $_POST['clave'] ?? '';

    if (empty($dni) || empty($clave)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        // Buscamos al usuario directamente por su DNI
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE dni = ?");
        $stmt->execute([$dni]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // --- CAMBIO DE LÓGICA DE LOGIN ---
        // Ahora verificamos si el usuario existe y si la clave ingresada es igual a su DNI.
        // ADVERTENCIA: Este método no es seguro para un entorno de producción.
        // Se utiliza solo porque fue solicitado explícitamente.
        if ($user && $clave === $user['dni']) {
            // Iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_rol'] = $user['rol'];
            redirect('dashboard.php');
        } else {
            $error = 'Usuario (DNI) o Clave (DNI) incorrectos.';
        }
    }
}

include 'partials/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="dni" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="dni" name="dni" required>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Clave</label>
                        <input type="password" class="form-control" id="clave" name="clave" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
