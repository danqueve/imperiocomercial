<?php
// view_form.php

// 1. INCLUIR ARCHIVOS DE CONFIGURACIÓN Y FUNCIONES
require_once 'lib/config.php';
require_once 'lib/functions.php';

// 2. VERIFICAR QUE EL USUARIO HAYA INICIADO SESIÓN
check_login();

// 3. OBTENER Y VALIDAR EL ID DEL FORMULARIO DESDE LA URL
$form_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$form_id) {
    // Si el ID no es válido o no existe, redirigir al dashboard.
    redirect('dashboard.php');
}

// 4. PREPARAR LA CONSULTA SQL PARA OBTENER LOS DATOS DEL FORMULARIO
// Se usa LEFT JOIN para que no falle si un formulario aún no tiene acción de un supervisor.
$sql = "
    SELECT f.*, 
           CONCAT(v.nombre, ' ', v.apellido) AS vendedor_nombre,
           CONCAT(s.nombre, ' ', s.apellido) AS supervisor_nombre
    FROM formularios f
    JOIN usuarios v ON f.vendedor_id = v.id
    LEFT JOIN usuarios s ON f.supervisor_id_accion = s.id
    WHERE f.id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$form_id]);
$form = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra el formulario, mostrar un mensaje y terminar.
if (!$form) {
    include 'partials/header.php';
    echo "<div class='alert alert-danger'>Error: Formulario no encontrado.</div>";
    include 'partials/footer.php';
    exit;
}

// 5. CONTROL DE ACCESO POR ROL
// Un vendedor solo puede ver los formularios que él mismo ha creado.
if ($_SESSION['user_rol'] === 'vendedor' && $form['vendedor_id'] != $_SESSION['user_id']) {
    redirect('dashboard.php');
}

// 6. INCLUIR LA CABECERA DE LA PÁGINA
include 'partials/header.php';
?>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h3 class="mb-0">Detalle del Formulario #<?= htmlspecialchars($form['id']) ?></h3>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <!-- Columna principal con los datos del cliente y la venta -->
            <div class="col-md-8">
                <h4><i class="fas fa-user-circle text-primary me-2"></i>Datos del Cliente</h4>
                <p><strong>Apellido y Nombre:</strong> <?= htmlspecialchars($form['cliente_apellido_nombre']) ?></p>
                <p><strong>DNI:</strong> <?= htmlspecialchars($form['cliente_dni']) ?></p>
                <p><strong>Domicilio:</strong> <?= htmlspecialchars($form['cliente_domicilio']) ?>, <?= htmlspecialchars($form['cliente_localidad']) ?> (Barrio: <?= htmlspecialchars($form['cliente_barrio'] ?? 'N/A') ?>)</p>
                <p><strong>Contacto:</strong> WhatsApp: <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $form['cliente_whatsapp']) ?>" target="_blank"><?= htmlspecialchars($form['cliente_whatsapp']) ?></a> / Llamadas: <?= htmlspecialchars($form['cliente_celular_llamada'] ?? 'N/A') ?></p>
                
                <hr class="my-4">
                
                <h4><i class="fas fa-briefcase text-primary me-2"></i>Datos Laborales</h4>
                <p><strong>Tipo de Empleo:</strong> <?= htmlspecialchars($form['cliente_tipo_empleo'] ?? 'No especificado') ?></p>
                <p><strong>Ocupación:</strong> <?= htmlspecialchars($form['cliente_de_que_trabaja'] ?? 'No especificado') ?></p>
                <p><strong>Lugar de Trabajo:</strong> <?= htmlspecialchars($form['cliente_nombre_trabajo'] ?? 'No especificado') ?> (<?= htmlspecialchars($form['cliente_domicilio_trabajo'] ?? 'N/A') ?>)</p>
                
                <hr class="my-4">

                <h4><i class="fas fa-box-open text-primary me-2"></i>Detalles de la Venta</h4>
                <p class="text-muted"><?= nl2br(htmlspecialchars($form['articulo_detalles'] ?? 'Sin detalles adicionales.')) ?></p>
            </div>
            
            <!-- Columna lateral con la información de estado del formulario -->
            <div class="col-md-4 bg-light p-3 rounded border">
                <h4><i class="fas fa-info-circle"></i>Información del Formulario</h4>
                <p><strong>Vendedor:</strong> <?= htmlspecialchars($form['vendedor_nombre']) ?></p>
                <p><strong>Fecha de Carga:</strong> <?= date('d/m/Y H:i', strtotime($form['fecha_creacion'])) ?></p>
                <hr>
                <p class="mb-2"><strong>Estado Actual:</strong></p>
                <?php
                    $estado = htmlspecialchars($form['estado']);
                    $badge_class = 'bg-secondary';
                    if ($estado == 'aprobado') $badge_class = 'bg-success';
                    if ($estado == 'rechazado') $badge_class = 'bg-danger';
                    if ($estado == 'en revision') $badge_class = 'bg-warning text-dark';
                ?>
                <p><span class="badge <?= $badge_class ?> fs-6 w-100"><?= ucfirst($estado) ?></span></p>
                
                <?php if ($form['estado'] === 'rechazado' && !empty($form['motivo_rechazo'])): ?>
                    <div class="alert alert-danger mt-3">
                        <strong class="d-block mb-1"><i class="fas fa-exclamation-triangle"></i> Motivo del Rechazo:</strong>
                        <p class="mb-0 fst-italic">"<?= htmlspecialchars($form['motivo_rechazo']) ?>"</p>
                    </div>
                <?php endif; ?>

                 <?php if ($form['supervisor_nombre']): ?>
                    <hr>
                    <p><strong>Última acción por:</strong> <?= htmlspecialchars($form['supervisor_nombre']) ?></p>
                    <p><strong>Fecha de acción:</strong> <?= date('d/m/Y H:i', strtotime($form['fecha_actualizacion_estado'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Volver al Dashboard</a>
    </div>
</div>

<?php 
// 7. INCLUIR EL PIE DE PÁGINA
include 'partials/footer.php'; 
?>
