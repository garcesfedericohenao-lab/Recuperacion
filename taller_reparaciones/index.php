<?php
/**
 * TechService - Portal Principal
 * Zona de Recibimiento y Búsqueda de Dispositivos por Código
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

$basePath = '';
$pageTitle = 'Zona de Recibimiento & Seguimiento de Reparaciones';

// 1. Estadísticas públicas para la Zona de Recibimiento
$statsPublic = [
    'activos'  => 0,
    'cerrados' => 0,
];
try {
    $stmtStats = $pdo->query("
        SELECT 
            SUM(CASE WHEN estado != 'Cerrado' THEN 1 ELSE 0 END) as activos,
            SUM(CASE WHEN estado = 'Cerrado' THEN 1 ELSE 0 END) as cerrados
        FROM servicios
    ");
    $row = $stmtStats->fetch();
    if ($row) {
        $statsPublic['activos']  = (int)($row['activos'] ?? 0);
        $statsPublic['cerrados'] = (int)($row['cerrados'] ?? 0);
    }
} catch (PDOException $e) {
    // Estadísticas opcionales en caso de falla de conexión
}

// 2. Consulta y búsqueda de servicio por número
$searchQuery = trim($_GET['numero'] ?? '');
$servicio = null;
$searched = false;
$error = null;

if (!empty($searchQuery)) {
    $searched = true;
    try {
        $stmt = $pdo->prepare("
            SELECT * 
            FROM servicios 
            WHERE UPPER(TRIM(numero_servicio)) = UPPER(:numero)
            LIMIT 1
        ");
        $stmt->execute(['numero' => $searchQuery]);
        $servicio = $stmt->fetch();

        if (!$servicio) {
            $error = "No se encontró ningún registro para el número de servicio <strong>" . e($searchQuery) . "</strong>. Por favor verifique el código asignado en su comprobante oficial.";
        }
    } catch (PDOException $e) {
        $error = "Error al realizar la consulta en la base de datos.";
    }
}

// 3. Determinar pestaña activa (Inicio vs Buscar)
$tabParam = trim($_GET['tab'] ?? '');
$activeTab = ($searched || $tabParam === 'buscar') ? 'buscar' : 'inicio';

include __DIR__ . '/includes/header.php';
?>

<!-- Selector de Pestañas Estilo Suizo -->
<div class="swiss-tab-nav">
    <ul class="nav nav-tabs" id="mainPortalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= ($activeTab === 'inicio') ? 'active' : ''; ?>" 
                    id="tab-inicio-btn" 
                    data-bs-toggle="tab" 
                    data-bs-target="#tab-inicio-pane" 
                    type="button" 
                    role="tab" 
                    aria-controls="tab-inicio-pane" 
                    aria-selected="<?= ($activeTab === 'inicio') ? 'true' : 'false'; ?>">
                <i class="fas fa-home me-2"></i> Zona de Recibimiento
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= ($activeTab === 'buscar') ? 'active' : ''; ?>" 
                    id="tab-buscar-btn" 
                    data-bs-toggle="tab" 
                    data-bs-target="#tab-buscar-pane" 
                    type="button" 
                    role="tab" 
                    aria-controls="tab-buscar-pane" 
                    aria-selected="<?= ($activeTab === 'buscar') ? 'true' : 'false'; ?>">
                <i class="fas fa-search me-2 text-danger"></i> Buscar Dispositivo por Código
                <?php if ($searched): ?>
                    <span class="badge bg-danger ms-2">Consulta Activa</span>
                <?php endif; ?>
            </button>
        </li>
    </ul>
</div>

<!-- Contenedor de Contenido de Pestañas -->
<div class="tab-content" id="mainPortalTabsContent">

    <!-- ============================================================= -->
    <!-- PESTAÑA 1: ZONA DE RECIBIMIENTO (INICIO)                      -->
    <!-- ============================================================= -->
    <div class="tab-pane fade <?= ($activeTab === 'inicio') ? 'show active' : ''; ?>" 
         id="tab-inicio-pane" 
         role="tabpanel" 
         aria-labelledby="tab-inicio-btn" 
         tabindex="0">

        <!-- Hero Principal con Imagen de Alta Precisión Técnica -->
        <div class="swiss-hero mb-5 p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="swiss-eyebrow">
                        <i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>ZONA DE RECIBIMIENTO & SERVICIO TÉCNICO
                    </span>
                    <h1 class="display-3 fw-bold letter-spacing-tight mb-3 text-dark">
                        Bienvenido a<br>
                        <span style="color: var(--swiss-red);">Tech</span>+Service
                    </h1>
                    <p class="lead text-secondary mb-4" style="max-width: 580px;">
                        Laboratorio técnico especializado en microelectrónica y reparación de alta precisión para smartphones, computadores, laptops y componentes electrónicos.
                        Consulte el avance y diagnóstico de su orden en tiempo real.
                    </p>

                    <!-- Acceso Principal a Búsqueda por Código -->
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <button type="button" class="btn btn-dark btn-lg fw-bold text-uppercase letter-spacing-1 px-4 py-3 rounded-0 shadow-sm js-goto-search">
                            <i class="fas fa-search me-2 text-danger"></i> Buscar Mi Dispositivo por Código
                        </button>
                        <?php if (isLoggedIn()): ?>
                            <a href="admin/dashboard.php" class="btn btn-outline-dark btn-lg fw-bold text-uppercase letter-spacing-1 px-4 py-3 rounded-0">
                                <i class="fas fa-tachometer-alt me-2"></i> Panel de Control
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-dark btn-lg fw-bold text-uppercase letter-spacing-1 px-4 py-3 rounded-0">
                                <i class="fas fa-lock me-2"></i> Acceso Administrativo
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Métricas Rápidas del Taller -->
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                        <div class="hero-stat-pill">
                            <i class="fas fa-tools text-primary fs-4"></i>
                            <div>
                                <div class="fs-4 fw-bold font-monospace text-dark lh-1"><?= $statsPublic['activos']; ?></div>
                                <div class="small text-muted text-uppercase fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.05em;">Servicios en Taller</div>
                            </div>
                        </div>
                        <div class="hero-stat-pill">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                            <div>
                                <div class="fs-4 fw-bold font-monospace text-success lh-1"><?= $statsPublic['cerrados']; ?></div>
                                <div class="small text-muted text-uppercase fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.05em;">Equipos Entregados</div>
                            </div>
                        </div>
                        <div class="hero-stat-pill">
                            <i class="fas fa-shield-alt text-danger fs-4"></i>
                            <div>
                                <div class="fs-4 fw-bold font-monospace text-dark lh-1">100%</div>
                                <div class="small text-muted text-uppercase fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.05em;">Garantía Certificada</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Vitrina Visual de Laboratorio / Reparación -->
                <div class="col-lg-5">
                    <div class="hero-tech-card">
                        <!-- Barra de Control Superior -->
                        <div class="hero-tech-header">
                            <span><i class="fas fa-microchip text-danger me-2"></i>HARDWARE LAB // BENCH #01</span>
                            <span class="badge bg-danger text-white"><i class="fas fa-circle me-1" style="font-size: 6px;"></i> EN SERVICIO</span>
                        </div>

                        <!-- Insignia Flotante -->
                        <div class="hero-tech-badge-float">
                            <i class="fas fa-certificate text-warning me-1"></i> ALTA PRECISIÓN
                        </div>

                        <!-- Imagen Principal -->
                        <div class="hero-tech-img-wrapper">
                            <img src="<?= $basePath; ?>assets/img/hero-repair.jpg" 
                                 alt="Técnico especialista reparando smartphone en laboratorio con herramientas de precisión" 
                                 class="hero-tech-img">
                            
                            <!-- Superposición Técnica en Parte Inferior -->
                            <div class="hero-tech-overlay">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <strong class="letter-spacing-tight">
                                        <i class="fas fa-screwdriver-wrench text-danger me-2"></i>Intervención Especializada
                                    </strong>
                                    <span class="badge bg-dark border border-secondary border-opacity-50 font-monospace">ESD-SAFE</span>
                                </div>
                                <p class="small text-white-50 mb-0">
                                    Micro-soldadura, cambio de pantallas, baterías, conectores y diagnóstico de circuitos integrados.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: ¿Cómo Funciona Nuestro Servicio? -->
        <div class="mb-5">
            <div class="text-center mb-4">
                <span class="swiss-eyebrow">FLUJO DE ATENCIÓN TÉCNICA</span>
                <h2 class="fw-bold letter-spacing-tight text-dark">¿Cómo funciona nuestro servicio?</h2>
                <p class="text-muted mx-auto" style="max-width: 550px;">Seguimos un proceso transparente de 4 etapas para garantizar la máxima calidad en cada reparación.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card card-custom h-100 p-4 text-center border border-dark">
                        <div class="bg-dark text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <span class="fw-bold fs-4 font-monospace">1</span>
                        </div>
                        <h5 class="fw-bold">1. Recepción</h5>
                        <p class="text-muted small mb-2">Registramos su dispositivo, documentamos la falla reportada y le emitimos su comprobante con código de seguimiento.</p>
                        <span class="badge bg-dark"><i class="fas fa-inbox me-1 text-danger"></i>RECIBIDO</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom h-100 p-4 text-center border">
                        <div class="bg-light text-dark border border-dark mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <span class="fw-bold fs-4 font-monospace">2</span>
                        </div>
                        <h5 class="fw-bold">2. Diagnóstico</h5>
                        <p class="text-muted small mb-2">Realizamos pruebas técnicas de laboratorio para identificar la causa exacta del problema y emitir diagnóstico.</p>
                        <span class="badge bg-light text-dark border border-dark"><i class="fas fa-microscope me-1 text-danger"></i>EN DIAGNÓSTICO</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom h-100 p-4 text-center border">
                        <div class="bg-secondary text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <span class="fw-bold fs-4 font-monospace">3</span>
                        </div>
                        <h5 class="fw-bold">3. Reparación</h5>
                        <p class="text-muted small mb-2">Intervenimos el equipo con piezas certificadas: soldadura, reemplazo de componentes y pruebas de estrés.</p>
                        <span class="badge bg-secondary"><i class="fas fa-wrench me-1"></i>EN REPARACIÓN</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom h-100 p-4 text-center border border-success">
                        <div class="bg-success text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <span class="fw-bold fs-4 font-monospace">4</span>
                        </div>
                        <h5 class="fw-bold">4. Entrega</h5>
                        <p class="text-muted small mb-2">Notificamos al cliente y entregamos el equipo con reporte técnico documentado y garantía de servicio.</p>
                        <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>CERRADO</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner CTA: Botón directo a la Pestaña de Búsqueda -->
        <div class="card card-custom border border-dark rounded-0 mb-5">
            <div class="card-body p-5 text-center">
                <i class="fas fa-laptop-medical fa-3x text-dark mb-3"></i>
                <h3 class="fw-bold letter-spacing-tight text-dark mb-2">¿Ya dejó su dispositivo en el taller?</h3>
                <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
                    Ingrese a la pestaña de consulta e ingrese el código de servicio que aparece en su comprobante para verificar el estado de su orden en tiempo real.
                </p>
                <button type="button" class="btn btn-dark btn-lg fw-bold text-uppercase letter-spacing-1 px-5 py-3 rounded-0 js-goto-search">
                    <i class="fas fa-barcode me-2 text-danger"></i> Consultar Estado de Mi Reparación
                </button>
            </div>
        </div>

        <!-- Sección: Dispositivos que Reparamos -->
        <div class="mb-5">
            <div class="text-center mb-4">
                <span class="swiss-eyebrow">EQUIPOS ATENDIDOS</span>
                <h2 class="fw-bold letter-spacing-tight text-dark">Dispositivos que Reparamos</h2>
            </div>

            <div class="row g-3">
                <div class="col-md-4 col-6">
                    <div class="card card-custom h-100 p-3 text-center border">
                        <i class="fas fa-mobile-alt fa-2x text-danger mb-2"></i>
                        <h6 class="fw-bold mb-1">Smartphones</h6>
                        <p class="text-muted small mb-0">Pantallas, baterías, puertos de carga, módulos y microsoldadura.</p>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card card-custom h-100 p-3 text-center border">
                        <i class="fas fa-laptop fa-2x text-dark mb-2"></i>
                        <h6 class="fw-bold mb-1">Laptops y Portátiles</h6>
                        <p class="text-muted small mb-0">Placa base, pantallas, teclados, bisagras y optimización.</p>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card card-custom h-100 p-3 text-center border">
                        <i class="fas fa-desktop fa-2x text-dark mb-2"></i>
                        <h6 class="fw-bold mb-1">PCs de Escritorio</h6>
                        <p class="text-muted small mb-0">Fuentes de poder, procesadores, RAM, GPUs y discos.</p>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card card-custom h-100 p-3 text-center border">
                        <i class="fas fa-tablet-alt fa-2x text-dark mb-2"></i>
                        <h6 class="fw-bold mb-1">Tablets</h6>
                        <p class="text-muted small mb-0">iPad, Samsung Galaxy Tab, Lenovo y multimarca.</p>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card card-custom h-100 p-3 text-center border">
                        <i class="fas fa-gamepad fa-2x text-dark mb-2"></i>
                        <h6 class="fw-bold mb-1">Consolas de Videojuegos</h6>
                        <p class="text-muted small mb-0">PlayStation, Xbox, Nintendo Switch, mandos y fuentes.</p>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card card-custom h-100 p-3 text-center border">
                        <i class="fas fa-print fa-2x text-dark mb-2"></i>
                        <h6 class="fw-bold mb-1">Impresoras y Periféricos</h6>
                        <p class="text-muted small mb-0">Mantenimiento preventivo, corrección de atascos y cabezales.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Informativa Final -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center border">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-shield-alt fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Garantía Certificada</h5>
                    <p class="text-muted small mb-0">Todas nuestras reparaciones cuentan con garantía de componentes y mano de obra calificada.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center border">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Diagnóstico Rápido</h5>
                    <p class="text-muted small mb-0">Evaluación técnica en las primeras 24-48 horas hábiles con reporte técnico detallado.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center border">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-id-card-alt fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Seguimiento en Línea</h5>
                    <p class="text-muted small mb-0">Consulte el avance de su equipo en cualquier momento desde su teléfono o computador.</p>
                </div>
            </div>
        </div>

    </div><!-- Fin Pestaña 1 -->


    <!-- ============================================================= -->
    <!-- PESTAÑA 2: BUSCAR DISPOSITIVO POR CÓDIGO                      -->
    <!-- ============================================================= -->
    <div class="tab-pane fade <?= ($activeTab === 'buscar') ? 'show active' : ''; ?>" 
         id="tab-buscar-pane" 
         role="tabpanel" 
         aria-labelledby="tab-buscar-btn" 
         tabindex="0">

        <!-- Barra de Retorno Rápido -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-outline-dark btn-sm rounded-0 fw-bold text-uppercase letter-spacing-1 js-goto-inicio">
                <i class="fas fa-arrow-left me-2"></i> Volver a Zona de Recibimiento
            </button>
            <span class="small text-muted font-monospace">CONSULTA PÚBLICA DE SERVICIOS</span>
        </div>

        <!-- Hero de Búsqueda -->
        <div class="swiss-hero mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="swiss-eyebrow"><i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>SEGUIMIENTO TÉCNICO EN LÍNEA</span>
                    <h1 class="display-4 fw-bold letter-spacing-tight mb-3 text-dark">
                        Consulta de Reparaciones
                    </h1>
                    <p class="lead text-secondary mb-4" style="max-width: 620px;">
                        Ingrese el identificador oficial de servicio asignado en su comprobante para conocer el estado técnico, pruebas de laboratorio y avance en tiempo real.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end d-none d-lg-block">
                    <div class="border-start border-2 border-dark ps-4 text-start">
                        <div class="small fw-bold text-uppercase text-muted letter-spacing-1">SISTEMA</div>
                        <div class="fs-5 fw-bold text-dark font-monospace">TECHSERVICE / v1.0</div>
                        <div class="small text-muted">CONTROL DE CALIDAD & DIAGNÓSTICO</div>
                    </div>
                </div>
            </div>

            <!-- Buscador Swiss Grid -->
            <div class="mt-2">
                <form action="index.php" method="GET" class="row g-0 swiss-search-form border border-dark">
                    <input type="hidden" name="tab" value="buscar">
                    <div class="col-md-9 d-flex align-items-center bg-white px-3 py-2">
                        <i class="fas fa-barcode text-muted me-3 fs-5"></i>
                        <input type="text" 
                               name="numero" 
                               class="form-control form-control-lg border-0 shadow-none swiss-search-input" 
                               placeholder="INGRESE NÚMERO DE SERVICIO (EJ: SRV-2026-0001)" 
                               value="<?= e($searchQuery); ?>" 
                               required 
                               autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-dark btn-lg w-100 h-100 rounded-0 fw-bold text-uppercase letter-spacing-1 px-4">
                            <i class="fas fa-search me-2"></i> Buscar Orden
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($searched): ?>
            <?php if ($servicio): ?>
                <?php 
                    $step = getStatusStep($servicio['estado']);
                ?>
                <!-- Resultado de la Búsqueda -->
                <div class="card card-custom mb-5 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between border-bottom">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                                <i class="fas fa-laptop-medical fs-4"></i>
                            </div>
                            <div>
                                <span class="text-muted small text-uppercase fw-semibold">Orden de Servicio</span>
                                <h4 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                    <span><?= e($servicio['numero_servicio']); ?></span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-code border-0 p-1" data-code="<?= e($servicio['numero_servicio']); ?>" title="Copiar código">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <?= renderStatusBadge($servicio['estado']); ?>
                            <a href="admin/ver.php?id=<?= $servicio['id']; ?>" class="btn btn-sm btn-outline-primary rounded-0" target="_blank">
                                <i class="fas fa-print me-1"></i> Imprimir Comprobante
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Stepper Visual de Avance -->
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Línea de Progreso del Servicio:</h6>
                            <div class="tracking-stepper">
                                <div class="step-item <?= ($step >= 1) ? ($step > 1 ? 'completed' : 'active') : ''; ?>">
                                    <div class="step-circle">
                                        <?php if ($step > 1): ?><i class="fas fa-check"></i><?php else: ?>1<?php endif; ?>
                                    </div>
                                    <div class="step-label">1. Recibido</div>
                                    <div class="text-muted small">Equipo en taller</div>
                                </div>

                                <div class="step-item <?= ($step >= 2) ? ($step > 2 ? 'completed' : 'active') : ''; ?>">
                                    <div class="step-circle">
                                        <?php if ($step > 2): ?><i class="fas fa-check"></i><?php else: ?>2<?php endif; ?>
                                    </div>
                                    <div class="step-label">2. En Diagnóstico</div>
                                    <div class="text-muted small">Revisión técnica</div>
                                </div>

                                <div class="step-item <?= ($step >= 3) ? ($step > 3 ? 'completed' : 'active') : ''; ?>">
                                    <div class="step-circle">
                                        <?php if ($step > 3): ?><i class="fas fa-check"></i><?php else: ?>3<?php endif; ?>
                                    </div>
                                    <div class="step-label">3. En Reparación</div>
                                    <div class="text-muted small">Intervención de hardware</div>
                                </div>

                                <div class="step-item <?= ($step >= 4) ? 'completed active' : ''; ?>">
                                    <div class="step-circle">
                                        <?php if ($step >= 4): ?><i class="fas fa-check-double"></i><?php else: ?>4<?php endif; ?>
                                    </div>
                                    <div class="step-label">4. Cerrado</div>
                                    <div class="text-muted small">Listo para entrega</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- Detalles de la Reparación -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-0 h-100 border">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                        <i class="fas fa-microchip text-primary me-2"></i>Información del Dispositivo
                                    </h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">Tipo:</td>
                                            <td class="fw-semibold"><?= e($servicio['tipo_dispositivo']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Marca:</td>
                                            <td class="fw-semibold"><?= e($servicio['marca']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Modelo:</td>
                                            <td class="fw-semibold"><?= e($servicio['modelo'] ?: 'No especificado'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Fecha de Ingreso:</td>
                                            <td><i class="far fa-calendar-alt text-muted me-1"></i><?= formatDate($servicio['fecha_ingreso']); ?></td>
                                        </tr>
                                        <?php if ($servicio['fecha_cierre']): ?>
                                            <tr>
                                                <td class="text-muted">Fecha de Cierre:</td>
                                                <td><i class="fas fa-check-circle text-success me-1"></i><?= formatDate($servicio['fecha_cierre']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-0 h-100 border">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                        <i class="fas fa-user text-primary me-2"></i>Datos del Cliente
                                    </h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">Titular:</td>
                                            <td class="fw-semibold"><?= e($servicio['cliente_nombre']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Teléfono de Contacto:</td>
                                            <td><?= e($servicio['cliente_telefono']); ?></td>
                                        </tr>
                                        <?php if (!empty($servicio['cliente_email'])): ?>
                                            <tr>
                                                <td class="text-muted">Correo Electrónico:</td>
                                                <td><?= e($servicio['cliente_email']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="text-muted">Técnico Asignado:</td>
                                            <td><span class="badge bg-secondary"><?= e($servicio['tecnico_asignado'] ?: 'Equipo Técnico'); ?></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Falla Reportada -->
                            <div class="col-12">
                                <div class="p-3 border rounded-0 bg-white">
                                    <h6 class="fw-bold text-danger mb-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Descripción del Daño / Falla Reportada:
                                    </h6>
                                    <p class="mb-0 text-secondary" style="white-space: pre-line;"><?= e($servicio['descripcion_dano']); ?></p>
                                </div>
                            </div>

                            <!-- Diagnóstico Técnico -->
                            <div class="col-12">
                                <div class="p-3 border rounded-0 <?= !empty($servicio['diagnostico']) ? 'bg-success bg-opacity-10 border-success' : 'bg-light'; ?>">
                                    <h6 class="fw-bold <?= !empty($servicio['diagnostico']) ? 'text-success' : 'text-muted'; ?> mb-2">
                                        <i class="fas fa-stethoscope me-2"></i>Diagnóstico y Estado de la Reparación:
                                    </h6>
                                    <?php if (!empty($servicio['diagnostico'])): ?>
                                        <p class="mb-0 text-dark fw-normal" style="white-space: pre-line;"><?= e($servicio['diagnostico']); ?></p>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted fst-italic">
                                            <i class="fas fa-hourglass-half me-1"></i> El equipo está en proceso de inspección. Aún no se ha emitido un diagnóstico técnico definitivo.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-center py-3">
                        <small class="text-muted">
                            ¿Tienes dudas sobre esta reparación? Comunícate con nuestro soporte técnico indicando el código <strong><?= e($servicio['numero_servicio']); ?></strong>.
                        </small>
                    </div>
                </div>
            <?php else: ?>
                <!-- Mensaje de Error en la Búsqueda -->
                <div class="alert alert-warning shadow-sm p-4 text-center rounded-0 mb-5" role="alert">
                    <div class="mb-3">
                        <i class="fas fa-search-minus fa-3x text-warning"></i>
                    </div>
                    <h4 class="alert-heading fw-bold">Número de Servicio No Encontrado</h4>
                    <p class="mb-3"><?= $error; ?></p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="index.php?tab=buscar" class="btn btn-outline-dark btn-sm rounded-0">
                            <i class="fas fa-arrow-left me-1"></i> Limpiar Búsqueda
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Ayuda Inicial cuando aún no ha buscado -->
            <div class="card card-custom border p-4 mb-4 bg-light">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <i class="fas fa-qrcode fa-3x text-muted"></i>
                    </div>
                    <div class="col-md-10">
                        <h6 class="fw-bold text-dark mb-1">¿Dónde encuentro mi código de servicio?</h6>
                        <p class="text-muted small mb-0">
                            Revise la parte superior derecha de su recibo o comprobante entregado al momento de dejar su equipo. El código tiene el formato <strong>SRV-<?= date('Y'); ?>-XXXX</strong>.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sección Informativa de Características del Taller -->
        <div class="row g-4 mt-2 mb-5">
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-shield-alt fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Garantía Certificada</h5>
                    <p class="text-muted small mb-0">Todas nuestras reparaciones electrónicas cuentan con garantía de componentes y mano de obra calificada.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Diagnóstico Preciso</h5>
                    <p class="text-muted small mb-0">Cada orden pasa por pruebas rigurosas de hardware antes de cerrar o entregar el dispositivo reparado.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-id-card-alt fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Seguimiento Transparente</h5>
                    <p class="text-muted small mb-0">Consulta en cualquier momento el estado de tu equipo desde tu celular, tablet o computador.</p>
                </div>
            </div>
        </div>

    </div><!-- Fin Pestaña 2 -->

</div><!-- Fin tab-content -->

<?php include __DIR__ . '/includes/footer.php'; ?>
