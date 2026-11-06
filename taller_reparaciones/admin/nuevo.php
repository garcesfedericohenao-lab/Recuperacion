<?php
/**
 * VISTA 3: Formulario de Registro de Nuevo Servicio Técnico (CRUD - Create)
 */

$basePath = '../';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

requireLogin('../login.php');

$pageTitle = 'Registrar Nueva Reparación';
$currentUser = currentUser();

$errores = [];
$valores = [
    'cliente_nombre'    => '',
    'cliente_telefono'  => '',
    'cliente_email'     => '',
    'tipo_dispositivo'  => 'Portátil / Laptop',
    'marca'             => '',
    'modelo'            => '',
    'descripcion_dano'  => '',
    'costo_estimado'    => '0.00',
    'tecnico_asignado'  => $currentUser['nombre'],
];

// Generar número de servicio sugerido
$numeroServicioSugerido = generateServiceNumber($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores['cliente_nombre']   = trim($_POST['cliente_nombre'] ?? '');
    $valores['cliente_telefono'] = trim($_POST['cliente_telefono'] ?? '');
    $valores['cliente_email']    = trim($_POST['cliente_email'] ?? '');
    $valores['tipo_dispositivo'] = trim($_POST['tipo_dispositivo'] ?? '');
    $valores['marca']            = trim($_POST['marca'] ?? '');
    $valores['modelo']           = trim($_POST['modelo'] ?? '');
    $valores['descripcion_dano'] = trim($_POST['descripcion_dano'] ?? '');
    $valores['costo_estimado']   = trim($_POST['costo_estimado'] ?? '0.00');
    $valores['tecnico_asignado'] = trim($_POST['tecnico_asignado'] ?? $currentUser['nombre']);

    // Validaciones básicas del lado del servidor
    if (empty($valores['cliente_nombre']) || mb_strlen($valores['cliente_nombre']) < 3) {
        $errores[] = 'El nombre del cliente es obligatorio y debe tener al menos 3 caracteres.';
    }

    if (empty($valores['cliente_telefono']) || mb_strlen($valores['cliente_telefono']) < 7) {
        $errores[] = 'El teléfono de contacto es obligatorio y debe tener al menos 7 dígitos.';
    }

    if (empty($valores['tipo_dispositivo'])) {
        $errores[] = 'Debe seleccionar un tipo de dispositivo.';
    }

    if (empty($valores['marca'])) {
        $errores[] = 'La marca del dispositivo es obligatoria.';
    }

    if (empty($valores['descripcion_dano']) || mb_strlen($valores['descripcion_dano']) < 10) {
        $errores[] = 'La descripción de la falla o daño reportado es obligatoria (mínimo 10 caracteres).';
    }

    $costo = filter_var($valores['costo_estimado'], FILTER_VALIDATE_FLOAT);
    if ($costo === false || $costo < 0) {
        $costo = 0.00;
    }

    // Si no hay errores, procedemos a insertar
    if (empty($errores)) {
        try {
            // Asegurar número de servicio único
            $numeroServicio = generateServiceNumber($pdo);

            $stmt = $pdo->prepare("
                INSERT INTO servicios (
                    numero_servicio, cliente_nombre, cliente_telefono, cliente_email,
                    tipo_dispositivo, marca, modelo, descripcion_dano,
                    diagnostico, estado, costo_estimado, tecnico_asignado,
                    fecha_ingreso
                ) VALUES (
                    :num, :cliente, :tel, :email,
                    :tipo, :marca, :modelo, :dano,
                    NULL, 'Recibido', :costo, :tecnico,
                    NOW()
                )
            ");

            $stmt->execute([
                'num'     => $numeroServicio,
                'cliente' => $valores['cliente_nombre'],
                'tel'     => $valores['cliente_telefono'],
                'email'   => $valores['cliente_email'] ?: null,
                'tipo'    => $valores['tipo_dispositivo'],
                'marca'   => $valores['marca'],
                'modelo'  => $valores['modelo'] ?: null,
                'dano'    => $valores['descripcion_dano'],
                'costo'   => $costo,
                'tecnico' => $valores['tecnico_asignado'] ?: $currentUser['nombre'],
            ]);

            $nuevoId = $pdo->lastInsertId();

            setFlash('success', "¡Orden de reparación registrada exitosamente! Se asignó el número de servicio: <strong>{$numeroServicio}</strong>.");
            header("Location: ver.php?id={$nuevoId}");
            exit;

        } catch (PDOException $e) {
            $errores[] = 'Error al registrar la orden en la base de datos: ' . $e->getMessage();
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
                    Nueva Orden de Reparación
                </h2>
            </div>
            <div class="text-end">
                <span class="swiss-eyebrow d-block mb-0">CÓDIGO ASIGNADO</span>
                <span class="badge bg-dark font-monospace fs-6 px-3 py-2 border border-dark">
                    <?= e($numeroServicioSugerido); ?>
                </span>
            </div>
        </div>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger border border-danger rounded-0 mb-4">
                <h6 class="fw-bold mb-2 text-uppercase letter-spacing-1"><i class="fas fa-exclamation-triangle me-2"></i>Errores de Validación:</h6>
                <ul class="mb-0 ps-3">
                    <?php foreach ($errores as $err): ?>
                        <li><?= e($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card card-custom border border-dark rounded-0">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="nuevo.php" id="formNuevoServicio" novalidate>
                    <!-- Sección 1: Datos del Cliente -->
                    <div class="border-bottom pb-4 mb-4">
                        <span class="swiss-eyebrow mb-1">SECCIÓN 01</span>
                        <h5 class="fw-bold text-dark mb-3 letter-spacing-tight">
                            Datos del Cliente
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cliente_nombre" class="form-label fw-semibold">Nombre Completo del Cliente <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" 
                                           value="<?= e($valores['cliente_nombre']); ?>" 
                                           placeholder="Ej: Carlos Mendoza" required minlength="3">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="cliente_telefono" class="form-label fw-semibold">Teléfono / WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="cliente_telefono" name="cliente_telefono" 
                                           value="<?= e($valores['cliente_telefono']); ?>" 
                                           placeholder="Ej: 3104567890" required minlength="7">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="cliente_email" class="form-label fw-semibold">Correo Electrónico <span class="text-muted small">(opcional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="cliente_email" name="cliente_email" 
                                           value="<?= e($valores['cliente_email']); ?>" 
                                           placeholder="cliente@email.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Datos del Dispositivo -->
                    <div class="border-bottom pb-4 mb-4">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fas fa-laptop text-primary me-2"></i>2. Información del Dispositivo
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="tipo_dispositivo" class="form-label fw-semibold">Tipo de Dispositivo <span class="text-danger">*</span></label>
                                <select class="form-select" id="tipo_dispositivo" name="tipo_dispositivo" required>
                                    <?php
                                    $tipos = [
                                        'Portátil / Laptop',
                                        'Computador de escritorio',
                                        'Smartphone',
                                        'Tablet',
                                        'Consola de videojuegos',
                                        'Servidor / Redes',
                                        'Impresora / Periférico',
                                        'Otro dispositivo electrónico'
                                    ];
                                    foreach ($tipos as $t):
                                    ?>
                                        <option value="<?= $t; ?>" <?= ($valores['tipo_dispositivo'] === $t) ? 'selected' : ''; ?>>
                                            <?= $t; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="marca" class="form-label fw-semibold">Marca <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="marca" name="marca" 
                                       value="<?= e($valores['marca']); ?>" 
                                       placeholder="Ej: Lenovo, Dell, HP, Apple, Samsung..." required>
                            </div>

                            <div class="col-md-4">
                                <label for="modelo" class="form-label fw-semibold">Modelo <span class="text-muted small">(opcional)</span></label>
                                <input type="text" class="form-control" id="modelo" name="modelo" 
                                       value="<?= e($valores['modelo']); ?>" 
                                       placeholder="Ej: ThinkPad T480 / Inspiron 15">
                            </div>
                        </div>
                    </div>

                    <!-- Sección 3: Descripción de la Falla y Configuración -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fas fa-file-medical text-primary me-2"></i>3. Detalle del Daño y Recepción
                        </h5>
                        
                        <div class="mb-3">
                            <label for="descripcion_dano" class="form-label fw-semibold">
                                Descripción del Daño / Falla Reportada <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="descripcion_dano" name="descripcion_dano" 
                                      rows="3" required minlength="10" 
                                      placeholder="Describa detalladamente los síntomas que presenta el equipo (ej: se apaga tras 10 minutos, no da video, pantalla rota, derrame de líquido...)"><?= e($valores['descripcion_dano']); ?></textarea>
                            <div class="form-text text-muted">Mínimo 10 caracteres. Proporcione la mayor cantidad de detalles posible.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="costo_estimado" class="form-label fw-semibold">Costo Estimado Inicial (COP)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="1000" min="0" class="form-control" id="costo_estimado" name="costo_estimado" 
                                           value="<?= e($valores['costo_estimado']); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="tecnico_asignado" class="form-label fw-semibold">Técnico Receptor / Asignado</label>
                                <input type="text" class="form-control" id="tecnico_asignado" name="tecnico_asignado" 
                                       value="<?= e($valores['tecnico_asignado']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle me-2 fs-5"></i>
                        <small>El estado inicial de la orden se registrará automáticamente como <strong>"Recibido"</strong> y se asignará un número de servicio correlativo único.</small>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="dashboard.php" class="btn btn-outline-dark px-4 rounded-0 fw-bold text-uppercase letter-spacing-1">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark px-4 py-2 rounded-0 fw-bold text-uppercase letter-spacing-1">
                            <i class="fas fa-save me-2 text-danger"></i> Registrar Orden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
