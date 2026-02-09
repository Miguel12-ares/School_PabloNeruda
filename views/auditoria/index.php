<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-history"></i> Auditoría del Sistema</h2>
            <p class="text-muted">Registro de actividades y acciones del sistema</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <a href="/index.php?controller=auditoria&action=exportar<?= http_build_query($filtros) ? '&' . http_build_query($filtros) : '' ?>" 
               class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Exportar CSV
            </a>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary"><?= number_format($estadisticas['total_logs'] ?? 0) ?></h3>
                    <small class="text-muted">Total de Logs</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success"><?= number_format($estadisticas['logs_hoy'] ?? 0) ?></h3>
                    <small class="text-muted">Logs Hoy</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-info"><?= number_format($estadisticas['usuarios_activos'] ?? 0) ?></h3>
                    <small class="text-muted">Usuarios Activos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-warning"><?= number_format($estadisticas['accesos_denegados'] ?? 0) ?></h3>
                    <small class="text-muted">Accesos Denegados</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="/index.php">
                <input type="hidden" name="controller" value="auditoria">
                <input type="hidden" name="action" value="index">
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="id_usuario" class="form-label">Usuario</label>
                        <select class="form-select" id="id_usuario" name="id_usuario">
                            <option value="">Todos</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id_usuario'] ?>" 
                                        <?= ($filtros['id_usuario'] ?? '') == $usuario['id_usuario'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($usuario['username']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="accion" class="form-label">Acción</label>
                        <select class="form-select" id="accion" name="accion">
                            <option value="">Todas</option>
                            <?php foreach ($acciones as $accion): ?>
                                <option value="<?= htmlspecialchars($accion) ?>" 
                                        <?= ($filtros['accion'] ?? '') === $accion ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($accion) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="modulo" class="form-label">Módulo</label>
                        <select class="form-select" id="modulo" name="modulo">
                            <option value="">Todos</option>
                            <?php foreach ($modulos as $modulo): ?>
                                <option value="<?= htmlspecialchars($modulo) ?>" 
                                        <?= ($filtros['modulo'] ?? '') === $modulo ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($modulo) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="fecha_desde" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" 
                               value="<?= htmlspecialchars($filtros['fecha_desde'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="fecha_hasta" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" 
                               value="<?= htmlspecialchars($filtros['fecha_hasta'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-1">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabla de Logs -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($logs)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No se encontraron registros con los filtros aplicados
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Fecha/Hora</th>
                                <th width="12%">Usuario</th>
                                <th width="15%">Acción</th>
                                <th width="10%">Módulo</th>
                                <th width="33%">Detalles</th>
                                <th width="10%">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= $log['id_auditoria'] ?></td>
                                    <td>
                                        <small><?= date('d/m/Y H:i:s', strtotime($log['fecha_accion'])) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($log['username'] ?? 'Sistema') ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeClass = 'secondary';
                                        if (strpos($log['accion'], 'CREAR') !== false) $badgeClass = 'success';
                                        elseif (strpos($log['accion'], 'ACTUALIZAR') !== false || strpos($log['accion'], 'EDITAR') !== false) $badgeClass = 'primary';
                                        elseif (strpos($log['accion'], 'ELIMINAR') !== false) $badgeClass = 'danger';
                                        elseif (strpos($log['accion'], 'LOGIN') !== false) $badgeClass = 'info';
                                        elseif (strpos($log['accion'], 'DENEGADO') !== false) $badgeClass = 'warning';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>">
                                            <?= htmlspecialchars($log['accion']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($log['modulo']) ?></small>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars(substr($log['detalles'], 0, 60)) ?><?= strlen($log['detalles']) > 60 ? '...' : '' ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($log['ip_address']) ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-muted">
                    <small>Mostrando <?= count($logs) ?> registros (límite: <?= $filtros['limite'] ?>)</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
