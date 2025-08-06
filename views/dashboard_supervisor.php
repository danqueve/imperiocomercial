<?php
// views/dashboard_supervisor.php
// Este archivo es incluido por dashboard.php, por lo que ya tiene acceso a la variable $formularios.
?>

<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="card-title"><i class="fas fa-file-signature me-2"></i>Gestionar Formularios de Venta</h4>
        <p class="card-subtitle mb-3 text-muted">Aquí puedes ver todos los formularios cargados y gestionar los que están pendientes de revisión.</p>
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
                                        <!-- Botón para Aprobar (acción directa) -->
                                        <button class="btn btn-sm btn-success form-action-btn" data-form-id="<?= $form['id'] ?>" data-action="aprobado" title="Aprobar"><i class="fas fa-check"></i></button>
                                        
                                        <!-- Botón para Rechazar (abre el modal) -->
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


<!-- MODAL PARA MOTIVO DE RECHAZO -->
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
            <!-- Campo oculto para guardar el ID del formulario -->
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
