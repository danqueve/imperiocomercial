<?php
// register.php
require_once 'lib/config.php';
require_once 'lib/functions.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanear datos
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_STRING);
    $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
    $domicilio = filter_input(INPUT_POST, 'domicilio', FILTER_SANITIZE_STRING);
    $localidad = filter_input(INPUT_POST, 'localidad', FILTER_SANITIZE_STRING);

    // Validaciones
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido.";
    if (empty($dni) || empty($nombre) || empty($apellido)) $errors[] = "Nombre, Apellido y DNI son obligatorios.";

    // Verificar si DNI o email ya existen
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? OR dni = ?");
        $stmt->execute([$email, $dni]);
        if ($stmt->fetch()) {
            $errors[] = "El email o DNI ya está registrado.";
        }
    }

    if (empty($errors)) {
        // La columna 'password' se omite, ya que ahora es opcional y no se usa.
        $sql = "INSERT INTO usuarios (email, dni, apellido, nombre, celular, domicilio, localidad, rol) VALUES (?, ?, ?, ?, ?, ?, ?, 'vendedor')";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$email, $dni, $apellido, $nombre, $celular, $domicilio, $localidad]);
            $success = "¡Registro exitoso! Ahora puedes <a href='login.php'>iniciar sesión</a> usando tu DNI como usuario y clave.";
        } catch (PDOException $e) {
            $errors[] = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}

include 'partials/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">Registro de Vendedor</h2>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php else: ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI (será tu usuario y clave)</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                    </div>
                     <div class="mb-3">
                        <label for="celular" class="form-label">Número de Celular</label>
                        <input type="text" class="form-control" id="celular" name="celular" required>
                    </div>
                    <div class="mb-3">
                        <label for="domicilio" class="form-label">Domicilio</label>
                        <input type="text" class="form-control" id="domicilio" name="domicilio" required>
                    </div>
                    <div class="mb-3">
                        <label for="localidad" class="form-label">Localidad</label>
                        <input type="text" class="form-control" id="localidad" name="localidad" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
