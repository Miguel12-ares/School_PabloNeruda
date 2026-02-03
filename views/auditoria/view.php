<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-file-text"></i> Detalle del Log #<?= $log['id_auditoria'] ?></h2>
            <p class="text-muted">Información completa del registro de auditoría</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/index.php?controller=auditoria&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="20%" class="bg-light">ID del Log</th>
                    <td><?= $log['id_auditoria'] ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Fecha y Hora</th>
                    <td><?= date('d/m/Y H:i:s', strtotime($log['fecha_accion'])) ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Usuario</th>
                    <td>
                        <strong><?= htmlspecialchars($log['username'] ?? 'Sistema') ?></strong>
                        <?php if ($log['id_usuario']): ?>
                            <small class="text-muted">(ID: <?= $log['id_usuario'] ?>)</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-light">Acción</th>
                    <td>
                        <span class="badge bg-primary"><?= htmlspecialchars($log['accion']) ?></span>
                    </td>
                </tr>
                <tr>
                    <th class="bg-light">Módulo</th>
                    <td><?= htmlspecialchars($log['modulo']) ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Detalles</th>
                    <td><?= htmlspecialchars($log['detalles']) ?></td>
                </tr>
                <tr>
                    <th class="bg-light">Dirección IP</th>
                    <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
