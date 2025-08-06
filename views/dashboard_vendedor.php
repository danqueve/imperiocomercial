<!-- views/dashboard_vendedor.php -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check-circle"></i> Aprobados</h5>
                <p class="card-text fs-2"><?= $stats['aprobado'] ?? 0 ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-hourglass-half"></i> En Revisión</h5>
                <p class="card-text fs-2"><?= $stats['en revision'] ?? 0 ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-times-circle"></i> Rechazados</h5>
                <p class="card-text fs-2"><?= $stats['rechazado'] ?? 0 ?></p>
            </div>
        </div>
    </div>
</div>

<h4>Mis Formularios Cargados</h4>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-header-custom">
            <tr>
                <th>#ID</th>
                <th>Cliente</th>
                <th>DNI Cliente</th>
                <th>Fecha de Carga</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($formularios)): ?>
                <tr><td colspan="6" class="text-center">Aún no has cargado ningún formulario.</td></tr>
            <?php else: ?>
                <?php foreach ($formularios as $form): ?>
                    <tr>
                        <td><?= htmlspecialchars($form['id']) ?></td>
                        <td><?= htmlspecialchars($form['cliente_apellido_nombre']) ?></td>
                        <td><?= htmlspecialchars($form['cliente_dni']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($form['fecha_creacion'])) ?></td>
                        <td>
                            <?php
                                $estado = htmlspecialchars($form['estado']);
                                $badge_class = '';
                                switch ($estado) {
                                    case 'aprobado': $badge_class = 'bg-success'; break;
                                    case 'rechazado': $badge_class = 'bg-danger'; break;
                                    default: $badge_class = 'bg-warning text-dark'; break;
                                }
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= ucfirst($estado) ?></span>
                        </td>
                        <td>
                            <a href="view_form.php?id=<?= $form['id'] ?>" class="btn btn-sm btn-info" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
