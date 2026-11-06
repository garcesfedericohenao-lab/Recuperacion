<?php
/**
 * Barra de Navegación Responsiva
 */
$user = currentUser();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= $basePath; ?>index.php">
            <span class="me-2 text-danger fw-black fs-4" style="line-height: 1;">■</span>
            <span>TECH<span class="swiss-cross">+</span>SERVICE</span>
            <span class="ms-2 px-1 text-muted border border-secondary border-opacity-50 font-monospace" style="font-size: 0.65rem;">SISTEMA v1.0</span>
        </a>

        <button class="navbar-toggler border-0 rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === 'index.php' && (!isset($activeTab) || $activeTab === 'inicio')) ? 'active' : ''; ?> js-goto-inicio" href="<?= $basePath; ?>index.php">
                        <i class="fas fa-home me-1"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === 'index.php' && (isset($activeTab) && $activeTab === 'buscar')) ? 'active' : ''; ?> js-goto-search" href="<?= $basePath; ?>index.php?tab=buscar">
                        <i class="fas fa-search me-1 text-danger"></i> Buscar Dispositivo
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'dashboard.php') ? 'active' : ''; ?>" href="<?= $basePath; ?>admin/dashboard.php">
                            Panel de Control
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'nuevo.php') ? 'active' : ''; ?>" href="<?= $basePath; ?>admin/nuevo.php">
                            Nueva Reparación
                        </a>
                    </li>
                    <?php if (isAdmin() && $user && (int)$user['id'] === 1): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'admins.php') ? 'active' : ''; ?>" href="<?= $basePath; ?>admin/admins.php">
                                <i class="fas fa-users-cog me-1"></i> Gestionar Admins
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if (isLoggedIn()): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm rounded-0 dropdown-toggle d-flex align-items-center py-2 px-3 text-uppercase letter-spacing-1 font-monospace" type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2 text-danger"></i>
                            <span class="me-2"><?= e($user['nombre']); ?></span>
                            <span class="badge <?= ($user['rol'] === 'admin') ? 'bg-danger' : 'bg-secondary'; ?>">
                                <?= e($user['rol']); ?>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border border-dark rounded-0 mt-2" aria-labelledby="userMenuBtn">
                            <li class="dropdown-header text-uppercase letter-spacing-1 small">Sesión: <strong><?= e($user['usuario']); ?></strong></li>
                            <li><a class="dropdown-item" href="<?= $basePath; ?>admin/dashboard.php"><i class="fas fa-list me-2 text-muted"></i>Todas las reparaciones</a></li>
                            <li><a class="dropdown-item" href="<?= $basePath; ?>admin/nuevo.php"><i class="fas fa-plus me-2 text-muted"></i>Registrar nuevo equipo</a></li>
                            <?php if (isAdmin() && $user && (int)$user['id'] === 1): ?>
                                <li><a class="dropdown-item" href="<?= $basePath; ?>admin/admins.php"><i class="fas fa-users-cog me-2 text-muted"></i>Gestionar Administradores</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger fw-bold text-uppercase letter-spacing-1 small" href="<?= $basePath; ?>logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= $basePath; ?>login.php" class="btn btn-danger btn-sm text-white fw-bold px-3 py-2 rounded-0 text-uppercase letter-spacing-1">
                        <i class="fas fa-lock me-1"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
