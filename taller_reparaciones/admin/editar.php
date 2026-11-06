<?php
/**
 * VISTA 4: Actualización del Diagnóstico y Estado del Servicio (CRUD - Update)
 * REGLA OBLIGATORIA: Validar que el diagnóstico no quede vacío al cerrar una reparación.
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

$pageTitle = 'Actualizar Servicio ' . $servicio['numero_servicio'];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoEstado       = trim($_POST['estado'] ?? $servicio['estado']);
    $nuevoDiagnostico  = trim($_POST['diagnostico'] ?? '');
    $nuevoCosto        = trim($_POST['costo_estimado'] ?? '0.00');
    $tecnicoAsignado   = trim($_POST['tecnico_asignado'] ?? $servicio['tecnico_asignado']);

    // Validar estado permitido
    $estadosValidos = ['Recibido', 'En diagnóstico', 'En reparación', 'Cerrado'];
    if (!in_array($nuevoEstado, $estadosValidos, true)) {
        $errores[] = 'El estado seleccionado no es válido.';
    }

    // =========================================================================
    // REQUISITO ESPECÍFICO: Validar que el diagnóstico no quede vacío al cerrar
    // =========================================================================
    if ($nuevoEstado === 'Cerrado' && empty($nuevoDiagnostico)) {
        $errores[] = '<strong>VALIDACIÓN OBLIGATORIA:</strong> No es posible cerrar o entregar una reparación con el campo de <em>Diagnóstico</em> vacío. Por favor ingrese el diagnóstico técnico y la solución aplicada.';
    }

    $costo = filter_var($nuevoCosto, FILTER_VALIDATE_FLOAT);
    if ($costo === false || $costo < 0) {
        $costo = 0.00;
    }

    if (empty($errores)) {
        try {
            // Manejo de fecha de cierre
            $fechaCierre = $servicio['fecha_cierre'];
            if ($nuevoEstado === 'Cerrado') {
                if (empty($fechaCierre)) {
                    $fechaCierre = date('Y-m-d H:i:s');
                }
            } else {
                // Si se reabre, limpiamos la fecha de cierre
                $fechaCierre = null;
            }

            $stmtUpdate = $pdo->prepare("
                UPDATE servicios SET
                    estado = :estado,
                    diagnostico = :diagnostico,
                    costo_estimado = :costo,
                    tecnico_asignado = :tecnico,
                    fecha_cierre = :fecha_cierre
                WHERE id = :id
            ");

            $stmtUpdate->execute([
                'estado'       => $nuevoEstado,
                'diagnostico'  => !empty($nuevoDiagnostico) ? $nuevoDiagnostico : null,
                'costo'        => $costo,
                'tecnico'      => $tecnicoAsignado,
                'fecha_cierre' => $fechaCierre,
                'id'           => $id
            ]);

            setFlash('success', "¡Servicio <strong>{$servicio['numero_servicio']}</strong> actualizado correctamente! Estado actual: <strong>{$nuevoEstado}</strong>.");
            header("Location: ver.php?id={$id}");
            exit;

        } catch (PDOException $e) {
            // Si viola el CHECK constraint de MySQL
            $errores[] = 'Error al actualizar el servicio en la base de datos: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-dark">
            <div>
                <a href="dashboard.php" class="text-decoration-none text-muted small text-uppercase letter-spacing-1">
                    <i class="fas fa-arrow-left me-1"></i> Panel de Control
                </a>
                <h2 class="fw-bold mb-0 mt-1 letter-spacing-tight text-dark">
                    Actualización de Diagnóstico & Estado
                </h2>
            </div>
            <div class="text-end">
                <span class="swiss-eyebrow d-block mb-0">N° ORDEN</span>
                <span class="badge bg-dark font-monospace fs-6 px-3 py-2 border border-dark">
                    <?= e($servicio['numero_servicio']); ?>
                </span>
            </div>
        </div>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger border border-danger rounded-0 mb-4">
                <h6 class="fw-bold mb-2 text-uppercase letter-spacing-1"><i class="fas fa-exclamation-triangle me-2"></i>Error de Validación:</h6>
                <ul class="mb-0 ps-3">
                    <?php foreach ($errores as $err): ?>
                        <li><?= $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Tarjeta Informativa Resumen del Equipo (Swiss Grid) -->
        <div class="card card-custom mb-4 border border-dark rounded-0 bg-white">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <span class="swiss-eyebrow text-muted mb-1">CLIENTE</span>
                        <div class="fw-bold text-dark fs-5"><?= e($servicio['cliente_nombre']); ?></div>
                        <div class="small text-muted font-monospace"><?= e($servicio['cliente_telefono']); ?></div>
                    </div>
                    <div class="col-md-4">
                        <span class="swiss-eyebrow text-muted mb-1">DISPOSITIVO</span>
                        <div class="fw-bold text-dark fs-5"><?= e($servicio['tipo_dispositivo']); ?></div>
                        <div class="small text-muted"><?= e($servicio['marca']); ?> <?= e($servicio['modelo']); ?></div>
                    </div>
                    <div class="col-md-4">
                        <span class="swiss-eyebrow text-muted mb-1">ESTADO ACTUAL</span>
                        <div class="mt-1"><?= renderStatusBadge($servicio['estado']); ?></div>
                    </div>
                    <div class="col-12 mt-2 pt-3 border-top">
                        <span class="swiss-eyebrow text-danger mb-1">FALLA REPORTADA INICIAL:</span>
                        <p class="mb-0 text-dark fst-italic">"<?= e($servicio['descripcion_dano']); ?>"</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Actualización -->
        <div class="card card-custom border border-dark rounded-0 shadow-none">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="editar.php?id=<?= $id; ?>" id="formEditarServicio" novalidate>
                    
                    <!-- Selección de Estado -->
                    <div class="mb-4">
                        <label for="estado" class="form-label fw-bold fs-5 text-dark">
                            <i class="fas fa-tasks text-primary me-2"></i>Estado del Servicio <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg" id="estado" name="estado" required>
                            <option value="Recibido" <?= ($servicio['estado'] === 'Recibido') ? 'selected' : ''; ?>>
                                📥 Recibido (Equipo en recepción / espera)
                            </option>
                            <option value="En diagnóstico" <?= ($servicio['estado'] === 'En diagnóstico') ? 'selected' : ''; ?>>
                                🔬 En diagnóstico (Revisión técnica y pruebas)
                            </option>
                            <option value="En reparación" <?= ($servicio['estado'] === 'En reparación') ? 'selected' : ''; ?>>
                                🔧 En reparación (Mano de obra y cambio de piezas)
                            </option>
                            <option value="Cerrado" <?= ($servicio['estado'] === 'Cerrado') ? 'selected' : ''; ?>>
                                ✅ Cerrado / Entregado (Reparación finalizada)
                            </option>
                        </select>
                        <div class="form-text mt-2 text-muted">
                            <i class="fas fa-info-circle me-1"></i> Recuerde: Si cambia el estado a <strong>"Cerrado"</strong>, el sistema exige obligatoriamente registrar el diagnóstico técnico.
                        </div>
                    </div>

                    <!-- Diagnóstico Técnico -->
                    <div class="mb-4">
                        <label for="diagnostico" class="form-label fw-bold fs-5 text-dark">
                            <i class="fas fa-stethoscope text-primary me-2"></i>Diagnóstico Técnico y Solución Aplicada
                        </label>
                        <textarea class="form-control" id="diagnostico" name="diagnostico" rows="5" 
                                  placeholder="Detalle los hallazgos técnicos, componentes afectados, pruebas realizadas y la solución implementada..."><?= e($_POST['diagnostico'] ?? $servicio['diagnostico']); ?></textarea>
                        <div id="diagnosticoHelp" class="form-text mt-2">
                            <span class="text-muted">Describa las pruebas realizadas, fallas detectadas y componentes reparados o cambiados.</span>
                        </div>
                    </div>

                    <!-- Costo y Técnico Asignado -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="costo_estimado" class="form-label fw-semibold">Costo Total / Estimado (COP)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">$</span>
                                <input type="number" step="1000" min="0" class="form-control" id="costo_estimado" name="costo_estimado" 
                                       value="<?= e($_POST['costo_estimado'] ?? $servicio['costo_estimado']); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="tecnico_asignado" class="form-label fw-semibold">Técnico Responsable</label>
                            <input type="text" class="form-control" id="tecnico_asignado" name="tecnico_asignado" 
                                   value="<?= e($_POST['tecnico_asignado'] ?? $servicio['tecnico_asignado']); ?>" required>
                        </div>
                    </div>

                    <!-- Botones de Acción (Swiss Style) -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="dashboard.php" class="btn btn-outline-dark px-4 rounded-0 fw-bold text-uppercase letter-spacing-1">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark btn-lg px-4 py-2 rounded-0 fw-bold text-uppercase letter-spacing-1">
                            <i class="fas fa-save me-2 text-danger"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
