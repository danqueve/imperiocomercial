<?php
// create_form.php
require_once 'lib/config.php';
require_once 'lib/functions.php';
check_login();

// ===================================================================
// == INICIO DEL CAMBIO: Permitir acceso a Vendedores Y Supervisores ==
// ===================================================================
if (!in_array($_SESSION['user_rol'], ['vendedor', 'supervisor'])) {
    // Si el rol no es ni 'vendedor' ni 'supervisor', se redirige.
    redirect('dashboard.php');
}
// ===================================================================
// == FIN DEL CAMBIO ==
// ===================================================================

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... (El resto del código para procesar el formulario no necesita cambios) ...
    $cliente_dni = filter_input(INPUT_POST, 'cliente_dni', FILTER_SANITIZE_STRING);
    $cliente_apellido_nombre = filter_input(INPUT_POST, 'cliente_apellido_nombre', FILTER_SANITIZE_STRING);
    $cliente_domicilio = filter_input(INPUT_POST, 'cliente_domicilio', FILTER_SANITIZE_STRING);
    $cliente_localidad = filter_input(INPUT_POST, 'cliente_localidad', FILTER_SANITIZE_STRING);
    $cliente_barrio = filter_input(INPUT_POST, 'cliente_barrio', FILTER_SANITIZE_STRING);
    $cliente_whatsapp = filter_input(INPUT_POST, 'cliente_whatsapp', FILTER_SANITIZE_STRING);
    $cliente_celular_llamada = filter_input(INPUT_POST, 'cliente_celular_llamada', FILTER_SANITIZE_STRING);
    $cliente_tipo_empleo = filter_input(INPUT_POST, 'cliente_tipo_empleo', FILTER_SANITIZE_STRING);
    $cliente_domicilio_trabajo = filter_input(INPUT_POST, 'cliente_domicilio_trabajo', FILTER_SANITIZE_STRING);
    $cliente_de_que_trabaja = filter_input(INPUT_POST, 'cliente_de_que_trabaja', FILTER_SANITIZE_STRING);
    $cliente_nombre_trabajo = filter_input(INPUT_POST, 'cliente_nombre_trabajo', FILTER_SANITIZE_STRING);
    $articulo_detalles = filter_input(INPUT_POST, 'articulo_detalles', FILTER_SANITIZE_STRING);

    if (empty($cliente_dni) || empty($cliente_apellido_nombre) || empty($cliente_whatsapp)) {
        $errors[] = 'DNI, Apellido y Nombre, y WhatsApp del cliente son obligatorios.';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO formularios (vendedor_id, cliente_dni, cliente_apellido_nombre, cliente_domicilio, cliente_localidad, cliente_barrio, cliente_whatsapp, cliente_celular_llamada, cliente_tipo_empleo, cliente_domicilio_trabajo, cliente_de_que_trabaja, cliente_nombre_trabajo, articulo_detalles) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                $_SESSION['user_id'], $cliente_dni, $cliente_apellido_nombre, $cliente_domicilio, $cliente_localidad, $cliente_barrio, $cliente_whatsapp, $cliente_celular_llamada, $cliente_tipo_empleo, $cliente_domicilio_trabajo, $cliente_de_que_trabaja, $cliente_nombre_trabajo, $articulo_detalles
            ]);
            $success = "Formulario cargado con éxito. Será revisado a la brevedad.";
        } catch (PDOException $e) {
            $errors[] = "Error al guardar el formulario: " . $e->getMessage();
        }
    }
}

include 'partials/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">Cargar Nueva Venta</h2>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul><?php foreach ($errors as $error) echo "<li>$error</li>"; ?></ul>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                    <a href="dashboard.php" class="btn btn-primary-custom">Volver al Dashboard</a>
                <?php else: ?>
                <form method="POST">
                    <!-- ... (El formulario HTML no necesita cambios) ... -->
                    <h5 class="mb-3">Datos del Cliente</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DNI Cliente</label>
                            <input type="text" name="cliente_dni" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido y Nombre</label>
                            <input type="text" name="cliente_apellido_nombre" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Domicilio de Residencia</label>
                            <input type="text" name="cliente_domicilio" class="form-control">
                        </div>
                         <div class="col-md-3 mb-3">
                            <label class="form-label">Localidad</label>
                            <input type="text" name="cliente_localidad" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Barrio</label>
                            <input type="text" name="cliente_barrio" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="cliente_whatsapp" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Celular para Llamadas</label>
                            <input type="text" name="cliente_celular_llamada" class="form-control">
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Datos Laborales del Cliente</h5>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Empleo</label>
                            <input type="text" name="cliente_tipo_empleo" class="form-control">
                        </div>
                         <div class="col-md-6 mb-3">
                            <label class="form-label">¿De Qué Trabaja?</label>
                            <input type="text" name="cliente_de_que_trabaja" class="form-control">
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Trabajo</label>
                            <input type="text" name="cliente_nombre_trabajo" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Domicilio del Trabajo</label>
                            <input type="text" name="cliente_domicilio_trabajo" class="form-control">
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Detalles Adicionales</h5>
                    <div class="mb-3">
                        <label class="form-label">Artículo / Detalles a Agregar</label>
                        <textarea name="articulo_detalles" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary-custom">Guardar Formulario</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
