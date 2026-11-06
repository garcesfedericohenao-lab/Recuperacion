<?php
/**
 * VISTA 5: Detalle Completo y Comprobante Imprimible de la Orden de Servicio
 */

$basePath = '../';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

requireLogin('../login.php');

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('danger', 'Identificador de servicio no válido.');
    header('Location: dashboard.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $servicio = $stmt->fetch();

    if (!$servicio) {
        setFlash('danger', 'La orden de reparación solicitada no existe.');
        header('Location: dashboard.php');
        exit;
    }
} catch (PDOException $e) {
    setFlash('danger', 'Error al consultar la orden: ' . $e->getMessage());
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Comprobante de Servicio ' . $servicio['numero_servicio'];
$currentUser = currentUser();

include __DIR__ . '/../includes/header.php';
?>

<!-- Barra de Navegación y Acciones Rápidas (Oculta al Imprimir) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 no-print pb-3 border-bottom border-dark">
    <div>
        <a href="dashboard.php" class="text-decoration-none text-muted small text-uppercase letter-spacing-1">
            <i class="fas fa-arrow-left me-1"></i> Panel de Control
        </a>
        <h3 class="fw-bold mb-0 mt-1 letter-spacing-tight text-dark">
            Comprobante Oficial de Servicio
        </h3>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-dark rounded-0 fw-bold text-uppercase letter-spacing-1" onclick="window.print();">
            <i class="fas fa-print me-1"></i> Imprimir
        </button>
        <a href="editar.php?id=<?= $servicio['id']; ?>" class="btn btn-dark rounded-0 fw-bold text-uppercase letter-spacing-1">
            <i class="fas fa-edit me-1 text-danger"></i> Actualizar
        </a>
        <a href="../index.php?numero=<?= urlencode($servicio['numero_servicio']); ?>" class="btn btn-outline-dark rounded-0 fw-bold text-uppercase letter-spacing-1" target="_blank">
            <i class="fas fa-external-link-alt me-1"></i> Rastreo Público
        </a>
    </div>
</div>

<!-- Contenedor del Ticket / Comprobante (Swiss Minimalist) -->
<div class="ticket-container mb-5">
    <!-- Encabezado del Comprobante -->
    <div class="row align-items-center border-bottom border-2 border-dark pb-4 mb-4">
        <div class="col-sm-7">
            <div class="d-flex align-items-center mb-2">
                <span class="me-2 text-danger fw-black fs-3">■</span>
                <div>
                    <h3 class="fw-bold mb-0 text-dark letter-spacing-tight">TECH+SERVICE</h3>
                    <small class="text-muted text-uppercase letter-spacing-1" style="font-size: 0.72rem;">LABORATORIO TÉCNICO ESPECIALIZADO</small>
                </div>
            </div>
            <div class="small text-muted font-monospace">
                <div>Av. Tecnológica # 45-20, Centro Empresarial</div>
                <div>soporte@techservice.com | PBX: (601) 789-0123</div>
            </div>
        </div>
        <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
            <div class="p-3 border border-dark text-center text-sm-end bg-white">
                <span class="swiss-eyebrow text-muted mb-0">N° ORDEN OFICIAL</span>
                <div class="fs-4 fw-bold font-monospace text-dark"><?= e($servicio['numero_servicio']); ?></div>
                <div class="mt-2"><?= renderStatusBadge($servicio['estado']); ?></div>
            </div>
        </div>
    </div>
                <div><i class="fas fa-map-marker-alt me-1 text-primary"></i> Av. Tecnológica # 45-20, Centro Empresarial</div>
                <div><i class="fas fa-envelope me-1 text-primary"></i> soporte@techservice.com | Tel: (601) 789-0123</div>
            </div>
        </div>
        <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
            <div class="p-3 bg-light rounded border text-center text-sm-end">
                <div class="text-muted small text-uppercase fw-bold">Número de Orden</div>
                <div class="fs-4 fw-bold font-monospace text-primary"><?= e($servicio['numero_servicio']); ?></div>
                <div class="mt-2"><?= renderStatusBadge($servicio['estado']); ?></div>
            </div>
        </div>
    </div>

    <!-- Fechas y Datos del Cliente -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="p-3 bg-light rounded h-100 border">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fas fa-user text-primary me-2"></i>Datos del Cliente
                </h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 40%;">Nombre:</td>
                        <td class="fw-bold"><?= e($servicio['cliente_nombre']); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Teléfono:</td>
                        <td class="fw-semibold"><?= e($servicio['cliente_telefono']); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Correo:</td>
                        <td><?= e($servicio['cliente_email'] ?: 'No registrado'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 bg-light rounded h-100 border">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fas fa-clock text-primary me-2"></i>Tiempos del Servicio
                </h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 45%;">Fecha de Ingreso:</td>
                        <td class="fw-semibold"><?= formatDate($servicio['fecha_ingreso']); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Última Actualización:</td>
                        <td><?= formatDate($servicio['fecha_actualizacion']); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fecha de Cierre:</td>
                        <td>
                            <?php if ($servicio['fecha_cierre']): ?>
                                <span class="badge bg-success"><?= formatDate($servicio['fecha_cierre']); ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">En proceso técnico</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Información del Dispositivo -->
    <div class="card mb-4 border">
        <div class="card-header bg-white py-2 fw-bold text-dark">
            <i class="fas fa-laptop text-primary me-2"></i>Detalles del Dispositivo Recibido
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <span class="text-muted small">Tipo:</span>
                    <div class="fw-bold fs-6"><?= e($servicio['tipo_dispositivo']); ?></div>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small">Marca:</span>
                    <div class="fw-bold fs-6"><?= e($servicio['marca']); ?></div>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small">Modelo:</span>
                    <div class="fw-bold fs-6"><?= e($servicio['modelo'] ?: 'N/A'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Falla Reportada (Swiss Layout) -->
    <div class="card mb-4 border border-dark rounded-0">
        <div class="card-header bg-white py-2 border-bottom border-dark">
            <span class="swiss-eyebrow text-danger mb-0">SÍNTOMAS & FALLA REPORTADA</span>
        </div>
        <div class="card-body">
            <p class="mb-0 text-dark" style="white-space: pre-line;"><?= e($servicio['descripcion_dano']); ?></p>
        </div>
    </div>

    <!-- Diagnóstico Técnico -->
    <div class="card mb-4 border border-dark rounded-0">
        <div class="card-header bg-white py-2 border-bottom border-dark">
            <span class="swiss-eyebrow text-dark mb-0">DIAGNÓSTICO TÉCNICO Y PROCEDIMIENTO</span>
        </div>
        <div class="card-body">
            <?php if (!empty($servicio['diagnostico'])): ?>
                <p class="mb-0 text-dark" style="white-space: pre-line;"><?= e($servicio['diagnostico']); ?></p>
            <?php else: ?>
                <p class="mb-0 text-muted fst-italic">Pendiente de diagnóstico técnico en laboratorio.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Costo y Firmas -->
    <div class="row align-items-center border-top border-dark pt-4 mt-4">
        <div class="col-sm-6">
            <span class="swiss-eyebrow text-muted mb-0">TÉCNICO RESPONSABLE</span>
            <div class="fw-bold text-dark fs-5"><?= e($servicio['tecnico_asignado'] ?: 'Laboratorio Central'); ?></div>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <span class="swiss-eyebrow text-muted mb-0">COSTO TOTAL DEL SERVICIO</span>
            <div class="fs-3 fw-bold text-dark font-monospace"><?= formatMoney($servicio['costo_estimado']); ?> COP</div>
        </div>
    </div>

    <!-- Términos y Firmas para Impresión -->
    <div class="mt-5 pt-4 border-top border-dark">
        <div class="row text-center mt-4">
            <div class="col-6">
                <div class="border-top border-dark mx-auto pt-2" style="max-width: 220px;">
                    <small class="fw-bold text-uppercase letter-spacing-1">Firma del Cliente</small>
                </div>
            </div>
            <div class="col-6">
                <div class="border-top border-dark mx-auto pt-2" style="max-width: 220px;">
                    <small class="fw-bold text-uppercase letter-spacing-1">Firma Técnico Receptor</small>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 small text-muted font-monospace">
            Conserve este comprobante oficial para retirar su equipo. Consulte el avance en línea con el código <strong><?= e($servicio['numero_servicio']); ?></strong>.
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
