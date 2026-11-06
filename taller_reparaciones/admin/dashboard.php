<?php
/**
 * VISTA 2: Panel de Administración y Gestión de Reparaciones (CRUD)
 */

$basePath = '../';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

// Requiere que el usuario esté autenticado (admin o usuario)
requireLogin('../login.php');

$pageTitle = 'Panel de Gestión de Reparaciones';
$currentUser = currentUser();

// Parámetros de búsqueda y filtrado
$busqueda = trim($_GET['q'] ?? '');
$filtroEstado = trim($_GET['estado'] ?? '');

// Métricas / Contadores
$counts = [
    'total'          => 0,
    'recibido'       => 0,
    'en_diagnostico' => 0,
    'en_reparacion'  => 0,
    'cerrado'        => 0,
];

try {
    $stmtCounts = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN estado = 'Recibido' THEN 1 ELSE 0 END) as recibido,
            SUM(CASE WHEN estado = 'En diagnóstico' THEN 1 ELSE 0 END) as en_diagnostico,
            SUM(CASE WHEN estado = 'En reparación' THEN 1 ELSE 0 END) as en_reparacion,
            SUM(CASE WHEN estado = 'Cerrado' THEN 1 ELSE 0 END) as cerrado
        FROM servicios
    ");
    $countsRow = $stmtCounts->fetch();
    if ($countsRow) {
        $counts['total']          = (int)$countsRow['total'];
        $counts['recibido']       = (int)$countsRow['recibido'];
        $counts['en_diagnostico'] = (int)$countsRow['en_diagnostico'];
        $counts['en_reparacion']  = (int)$countsRow['en_reparacion'];
        $counts['cerrado']        = (int)$countsRow['cerrado'];
    }

    // Construcción de la consulta con filtros dinámicos y prepared statements
    $sql = "SELECT * FROM servicios WHERE 1=1";
    $params = [];

    if (!empty($busqueda)) {
        $sql .= " AND (
            numero_servicio LIKE :busqueda 
            OR cliente_nombre LIKE :busqueda 
            OR cliente_telefono LIKE :busqueda 
            OR tipo_dispositivo LIKE :busqueda 
            OR marca LIKE :busqueda 
            OR modelo LIKE :busqueda
        )";
        $params['busqueda'] = "%{$busqueda}%";
    }

    if (!empty($filtroEstado)) {
        $sql .= " AND estado = :estado";
        $params['estado'] = $filtroEstado;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $servicios = $stmt->fetchAll();
} catch (PDOException $e) {
    setFlash('danger', 'Error al obtener la lista de reparaciones: ' . $e->getMessage());
    $servicios = [];
}

include __DIR__ . '/../includes/header.php';
?>

<!-- Encabezado del Panel (Swiss Style) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom border-dark">
    <div>
        <span class="swiss-eyebrow"><i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>PANEL DE CONTROL</span>
        <h2 class="fw-bold mb-1 letter-spacing-tight text-dark">
            Gestión de Reparaciones
        </h2>
        <p class="text-muted small mb-0 font-monospace">
            OPERADOR: <strong><?= e($currentUser['nombre']); ?></strong> | ROL: 
            <span class="badge <?= ($currentUser['rol'] === 'admin') ? 'bg-danger' : 'bg-dark'; ?> text-uppercase">
                <?= e($currentUser['rol']); ?>
            </span>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="nuevo.php" class="btn btn-dark fw-bold text-uppercase letter-spacing-1 px-3 py-2 rounded-0">
            <i class="fas fa-plus me-1 text-danger"></i> Nueva Orden
        </a>
        <?php if (isAdmin() && (int)$currentUser['id'] === 1): ?>
            <a href="admins.php" class="btn btn-danger fw-bold text-uppercase letter-spacing-1 px-3 py-2 rounded-0">
                <i class="fas fa-users-cog me-1"></i> Gestionar Admins
            </a>
        <?php endif; ?>
        <a href="../index.php" class="btn btn-outline-dark fw-bold text-uppercase letter-spacing-1 px-3 py-2 rounded-0" target="_blank">
            <i class="fas fa-external-link-alt me-1"></i> Vista Pública
        </a>
    </div>
</div>

<!-- Tarjetas de Métricas Estadísticas (Swiss Grid) -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-white p-3 border border-dark">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="swiss-eyebrow text-muted mb-1">TOTAL ÓRDENES</span>
                    <h2 class="fw-bold mb-0 text-dark font-monospace"><?= $counts['total']; ?></h2>
                </div>
                <div class="text-dark fs-3">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-white p-3 border border-dark">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="swiss-eyebrow text-muted mb-1">EN DIAGNÓSTICO</span>
                    <h2 class="fw-bold mb-0 text-dark font-monospace"><?= $counts['en_diagnostico']; ?></h2>
                </div>
                <div class="text-danger fs-3">
                    <i class="fas fa-microscope"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-white p-3 border border-dark">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="swiss-eyebrow text-muted mb-1">EN REPARACIÓN</span>
                    <h2 class="fw-bold mb-0 text-dark font-monospace"><?= $counts['en_reparacion']; ?></h2>
                </div>
                <div class="text-dark fs-3">
                    <i class="fas fa-wrench"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-white p-3 border border-dark">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="swiss-eyebrow text-muted mb-1">CERRADAS</span>
                    <h2 class="fw-bold mb-0 text-dark font-monospace"><?= $counts['cerrado']; ?></h2>
                </div>
                <div class="text-dark fs-3">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barra de Filtros y Búsqueda -->
<div class="card card-custom mb-4 border border-dark rounded-0">
    <div class="card-body p-3">
        <form method="GET" action="dashboard.php" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted rounded-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 rounded-0" 
                           placeholder="Buscar por N° Servicio, cliente, teléfono o marca..." 
                           value="<?= e($busqueda); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="estado" class="form-select rounded-0">
                    <option value="">-- FILTRAR POR ESTADO (TODOS) --</option>
                    <option value="Recibido" <?= ($filtroEstado === 'Recibido') ? 'selected' : ''; ?>>Recibido</option>
                    <option value="En diagnóstico" <?= ($filtroEstado === 'En diagnóstico') ? 'selected' : ''; ?>>En diagnóstico</option>
                    <option value="En reparación" <?= ($filtroEstado === 'En reparación') ? 'selected' : ''; ?>>En reparación</option>
                    <option value="Cerrado" <?= ($filtroEstado === 'Cerrado') ? 'selected' : ''; ?>>Cerrado / Entregado</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold text-uppercase letter-spacing-1 rounded-0">
                    Filtrar
                </button>
                <?php if (!empty($busqueda) || !empty($filtroEstado)): ?>
                    <a href="dashboard.php" class="btn btn-outline-secondary rounded-0" title="Limpiar filtros">
                        <i class="fas fa-undo"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Servicios / Reparaciones -->
<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="fas fa-list-alt me-2 text-primary"></i>Registros de Reparación
            <span class="badge bg-secondary ms-2"><?= count($servicios); ?> registros</span>
        </h5>
        <span class="small text-muted">Ordenados por los más recientes</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 140px;">N° Servicio</th>
                    <th>Cliente</th>
                    <th>Dispositivo</th>
                    <th>Falla Reportada</th>
                    <th>Estado</th>
                    <th>Ingreso</th>
                    <th class="text-center" style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($servicios)): ?>
                    <?php foreach ($servicios as $s): ?>
                        <tr>
                            <td>
                                <strong class="text-primary font-monospace"><?= e($s['numero_servicio']); ?></strong>
                            </td>
                            <td>
                                <div class="fw-bold"><?= e($s['cliente_nombre']); ?></div>
                                <div class="small text-muted"><i class="fas fa-phone-alt me-1"></i><?= e($s['cliente_telefono']); ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= e($s['tipo_dispositivo']); ?></span>
                                <div class="small fw-semibold text-secondary mt-1"><?= e($s['marca']); ?> <?= e($s['modelo']); ?></div>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 230px;" title="<?= e($s['descripcion_dano']); ?>">
                                    <?= e($s['descripcion_dano']); ?>
                                </span>
                            </td>
                            <td>
                                <?= renderStatusBadge($s['estado']); ?>
                                <?php if (!empty($s['diagnostico'])): ?>
                                    <div class="small text-success mt-1" title="Diagnóstico registrado">
                                        <i class="fas fa-clipboard-check me-1"></i>Con diagnóstico
                                    </div>
                                <?php else: ?>
                                    <div class="small text-muted mt-1 fst-italic">
                                        <i class="fas fa-clock me-1"></i>Sin diagnóstico
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small text-nowrap"><?= formatDate($s['fecha_ingreso']); ?></div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Ver Voucher / Ticket -->
                                    <a href="ver.php?id=<?= $s['id']; ?>" class="btn btn-outline-info" title="Ver Detalle y Comprobante">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Editar Diagnóstico y Estado -->
                                    <a href="editar.php?id=<?= $s['id']; ?>" class="btn btn-outline-primary" title="Actualizar Diagnóstico y Estado">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Eliminar registro de prueba (CRUD Delete) -->
                                    <?php if ($currentUser['rol'] === 'admin'): ?>
                                        <a href="eliminar.php?id=<?= $s['id']; ?>" 
                                           class="btn btn-outline-danger btn-eliminar-servicio" 
                                           data-numero="<?= e($s['numero_servicio']); ?>"
                                           data-cliente="<?= e($s['cliente_nombre']); ?>"
                                           title="Eliminar Registro de Prueba">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-search-minus fa-3x mb-3 text-secondary opacity-50"></i>
                            <h6>No se encontraron registros de reparaciones</h6>
                            <p class="small mb-3">Intenta cambiar los términos de búsqueda o registra una nueva orden.</p>
                            <a href="nuevo.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Registrar Nueva Orden
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white py-3 text-muted small d-flex justify-content-between align-items-center">
        <span>Mostrando <strong><?= count($servicios); ?></strong> órdenes de servicio</span>
        <span>Base de datos: <strong>MySQL (taller_reparaciones)</strong></span>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
