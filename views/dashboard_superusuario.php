<?php
// views/dashboard_superusuario.php
?>

<!-- 1. NAVEGACIÓN POR PESTAÑAS (TABS) -->
<ul class="nav nav-tabs" id="superUserTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="forms-tab" data-bs-toggle="tab" data-bs-target="#forms-tab-pane" type="button" role="tab" aria-controls="forms-tab-pane" aria-selected="true">
        <i class="fas fa-file-signature me-1"></i> Gestionar Formularios
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane" type="button" role="tab" aria-controls="users-tab-pane" aria-selected="false">
        <i class="fas fa-users-cog me-1"></i> Gestionar Usuarios
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="log-tab" data-bs-toggle="tab" data-bs-target="#log-tab-pane" type="button" role="tab" aria-controls="log-tab-pane" aria-selected="false">
        <i class="fas fa-history me-1"></i> Log de Actividad
    </button>
  </li>
</ul>

<!-- 2. CONTENIDO DE LAS PESTAÑAS -->
<div class="tab-content" id="superUserTabContent">

  <!-- PESTAÑA 1: GESTIONAR FORMULARIOS -->
  <div class="tab-pane fade show active" id="forms-tab-pane" role="tabpanel" aria-labelledby="forms-tab" tabindex="0">
    <div class="card mt-3">
        <div class="card-body">
            <h4 class="card-title">Formularios Pendientes y Gestionados</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Vendedor</th>
                            <th>Cliente</th>
                            <th>Fecha Carga</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($formularios)): ?>
                            <tr><td colspan="6" class="text-center">No hay formularios para gestionar.</td></tr>
                        <?php else: ?>
                            <?php foreach ($formularios as $form): ?>
                                <tr>
                                    <td><?= htmlspecialchars($form['id']) ?></td>
                                    <td><?= htmlspecialchars($form['vendedor_nombre']) ?></td>
                                    <td><?= htmlspecialchars($form['cliente_apellido_nombre']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($form['fecha_creacion'])) ?></td>
                                    <td>
                                        <?php
                                            $estado = htmlspecialchars($form['estado']);
                                            $badge_class = 'bg-secondary';
                                            if ($estado == 'aprobado') $badge_class = 'bg-success';
                                            if ($estado == 'rechazado') $badge_class = 'bg-danger';
                                            if ($estado == 'en revision') $badge_class = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= ucfirst($estado) ?></span>
                                    </td>
                                    <td>
                                        <a href="view_form.php?id=<?= $form['id'] ?>" class="btn btn-sm btn-info" title="Ver Detalles"><i class="fas fa-eye"></i></a>
                                        <?php if ($form['estado'] === 'en revision'): ?>
                                            <button class="btn btn-sm btn-success form-action-btn" data-form-id="<?= $form['id'] ?>" data-action="aprobado" title="Aprobar"><i class="fas fa-check"></i></button>
                                            <!-- Este botón ahora funcionará porque el modal existe en la página -->
                                            <button class="btn btn-sm btn-danger reject-btn" data-bs-toggle="modal" data-bs-target="#rejectModal" data-form-id="<?= $form['id'] ?>" title="Rechazar"><i class="fas fa-times"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

  <!-- PESTAÑA 2: GESTIONAR USUARIOS -->
  <div class="tab-pane fade" id="users-tab-pane" role="tabpanel" aria-labelledby="users-tab" tabindex="0">
    <!-- ... (El contenido de esta pestaña no cambia) ... -->
    <div class="card mt-3">
        <div class="card-body">
            <h4 class="card-title">Gestionar Roles de Usuario</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>DNI</th>
                            <th>Cambiar Rol a</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['apellido'] . ', ' . $usuario['nombre']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><?= htmlspecialchars($usuario['dni']) ?></td>
                            <td>
                                <select class="form-select form-select-sm user-role-selector" data-user-id="<?= $usuario['id'] ?>">
                                    <option value="vendedor" <?= $usuario['rol'] === 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
                                    <option value="supervisor" <?= $usuario['rol'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

  <!-- PESTAÑA 3: LOG DE ACTIVIDAD -->
  <div class="tab-pane fade" id="log-tab-pane" role="tabpanel" aria-labelledby="log-tab" tabindex="0">
     <!-- ... (El contenido de esta pestaña no cambia) ... -->
     <div class="card mt-3">
        <div class="card-body">
            <h4 class="card-title">Historial de Cambios de Estado</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#Form</th>
                            <th>Cliente</th>
                            <th>Nuevo Estado</th>
                            <th>Fecha y Hora</th>
                            <th>Realizado por</th>
                        </tr>
                    </thead>
                    <tbody>
                         <?php if (empty($log_actividad)): ?>
                            <tr><td colspan="5" class="text-center">Aún no hay actividad registrada.</td></tr>
                        <?php else: ?>
                            <?php foreach ($log_actividad as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['id']) ?></td>
                                <td><?= htmlspecialchars($log['cliente_apellido_nombre']) ?></td>
                                <td>
                                    <?php
                                        $estado_log = htmlspecialchars($log['estado']);
                                        $badge_class_log = 'bg-info';
                                        if ($estado_log == 'aprobado') $badge_class_log = 'bg-success';
                                        if ($estado_log == 'rechazado') $badge_class_log = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class_log ?>"><?= ucfirst($estado_log) ?></span>
                                </td>
                                <td><?= date('d/m/Y H:i:s', strtotime($log['fecha_actualizacion_estado'])) ?></td>
                                <td><?= htmlspecialchars($log['supervisor_nombre']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- =================================================================== -->
<!-- == INICIO DEL CÓDIGO AÑADIDO: MODAL PARA MOTIVO DE RECHAZO == -->
<!-- =================================================================== -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Motivo del Rechazo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Por favor, especifica claramente por qué se rechaza este formulario.</p>
        <form id="rejectForm">
            <input type="hidden" id="formIdToReject" name="form_id">
            <div class="mb-3">
                <label for="rejectionReason" class="form-label">Motivo:</label>
                <textarea class="form-control" id="rejectionReason" name="reason" rows="4" required></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmRejectBtn">Confirmar Rechazo</button>
      </div>
    </div>
  </div>
</div>
<!-- =================================================================== -->
<!-- == FIN DEL CÓDIGO AÑADIDO == -->
<!-- =================================================================== -->
