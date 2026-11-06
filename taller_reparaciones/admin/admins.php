<?php
/**
 * VISTA: Gestión de Administradores
 * Solo el admin principal (id=1) puede agregar, editar y eliminar otros admins.
 */

$basePath = '../';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

requireLogin('../login.php');

// Solo el admin principal (primer admin registrado, id=1) puede acceder
$currentUser = currentUser();
if (!isAdmin() || (int)$currentUser['id'] !== 1) {
    setFlash('danger', 'Acceso denegado: Solo el Administrador Principal puede gestionar otros administradores.');
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Gestión de Administradores';
$errores = [];
$exito = false;

// --- Procesar eliminación de admin ---
if (isset($_GET['eliminar'])) {
    $eliminarId = filter_var($_GET['eliminar'], FILTER_VALIDATE_INT);
    if ($eliminarId && $eliminarId !== 1) {
        try {
            $stmtCheck = $pdo->prepare("SELECT usuario, nombre FROM usuarios WHERE id = :id AND id != 1 LIMIT 1");
            $stmtCheck->execute(['id' => $eliminarId]);
            $adminEliminar = $stmtCheck->fetch();

            if ($adminEliminar) {
                $stmtDel = $pdo->prepare("DELETE FROM usuarios WHERE id = :id AND id != 1");
                $stmtDel->execute(['id' => $eliminarId]);
                setFlash('success', "El administrador <strong>" . e($adminEliminar['nombre']) . "</strong> (@" . e($adminEliminar['usuario']) . ") ha sido eliminado exitosamente.");
            } else {
                setFlash('warning', 'El administrador que intenta eliminar no existe o no se puede eliminar.');
            }
        } catch (PDOException $e) {
            setFlash('danger', 'Error al eliminar el administrador: ' . $e->getMessage());
        }
    } else {
        setFlash('danger', 'No se puede eliminar al Administrador Principal del sistema.');
    }
    header('Location: admins.php');
    exit;
}

// --- Procesar creación de nuevo admin ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoUsuario = trim($_POST['nuevo_usuario'] ?? '');
    $nuevoNombre  = trim($_POST['nuevo_nombre'] ?? '');
    $nuevoPass    = $_POST['nuevo_password'] ?? '';
    $confirmarPass = $_POST['confirmar_password'] ?? '';
    $nuevoRol     = trim($_POST['nuevo_rol'] ?? 'admin');

    // Validaciones
    if (empty($nuevoUsuario) || mb_strlen($nuevoUsuario) < 3) {
        $errores[] = 'El nombre de usuario es obligatorio y debe tener al menos 3 caracteres.';
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $nuevoUsuario)) {
        $errores[] = 'El nombre de usuario solo puede contener letras, números y guiones bajos.';
    }

    if (empty($nuevoNombre) || mb_strlen($nuevoNombre) < 3) {
        $errores[] = 'El nombre completo es obligatorio y debe tener al menos 3 caracteres.';
    }

    if (empty($nuevoPass) || mb_strlen($nuevoPass) < 6) {
        $errores[] = 'La contraseña es obligatoria y debe tener al menos 6 caracteres.';
    }

    if ($nuevoPass !== $confirmarPass) {
        $errores[] = 'Las contraseñas no coinciden. Verifique ambos campos.';
    }

    if (!in_array($nuevoRol, ['admin', 'usuario'], true)) {
        $nuevoRol = 'admin';
    }

    // Verificar que el usuario no exista ya
    if (empty($errores)) {
        try {
            $stmtExists = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario LIMIT 1");
            $stmtExists->execute(['usuario' => $nuevoUsuario]);
            if ($stmtExists->fetch()) {
                $errores[] = "El nombre de usuario <strong>" . e($nuevoUsuario) . "</strong> ya está registrado. Elija otro.";
            }
        } catch (PDOException $e) {
            $errores[] = 'Error al verificar el usuario: ' . $e->getMessage();
        }
    }

    // Insertar nuevo admin
    if (empty($errores)) {
        try {
            $hashedPass = password_hash($nuevoPass, PASSWORD_DEFAULT);
            $stmtInsert = $pdo->prepare("
                INSERT INTO usuarios (usuario, password, nombre, rol)
                VALUES (:usuario, :password, :nombre, :rol)
            ");
            $stmtInsert->execute([
                'usuario'  => $nuevoUsuario,
                'password' => $hashedPass,
                'nombre'   => $nuevoNombre,
                'rol'      => $nuevoRol,
            ]);

            $rolLabel = ($nuevoRol === 'admin') ? 'Administrador' : 'Usuario';
            setFlash('success', "¡{$rolLabel} <strong>" . e($nuevoNombre) . "</strong> (@" . e($nuevoUsuario) . ") creado exitosamente!");
            header('Location: admins.php');
            exit;
        } catch (PDOException $e) {
            $errores[] = 'Error al crear el administrador: ' . $e->getMessage();
        }
    }
}

// --- Obtener lista de todos los admins ---
try {
    $stmtAdmins = $pdo->query("SELECT id, usuario, nombre, rol, creado_en FROM usuarios ORDER BY id ASC");
    $admins = $stmtAdmins->fetchAll();
} catch (PDOException $e) {
    $admins = [];
    setFlash('danger', 'Error al obtener la lista de administradores.');
}

include __DIR__ . '/../includes/header.php';
?>

<!-- Encabezado -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom border-dark">
    <div>
        <a href="dashboard.php" class="text-decoration-none text-muted small text-uppercase letter-spacing-1">
            <i class="fas fa-arrow-left me-1"></i> Panel de Control
        </a>
        <span class="swiss-eyebrow d-block mt-2"><i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>ADMINISTRACIÓN DE USUARIOS</span>
        <h2 class="fw-bold mb-1 letter-spacing-tight text-dark">
            Gestión de Administradores
        </h2>
        <p class="text-muted small mb-0 font-monospace">
            ADMIN PRINCIPAL: <strong><?= e($currentUser['nombre']); ?></strong> | Solo tú puedes gestionar usuarios del sistema.
        </p>
    </div>
    <div>
        <a href="dashboard.php" class="btn btn-outline-dark fw-bold text-uppercase letter-spacing-1 px-3 py-2 rounded-0">
            <i class="fas fa-tachometer-alt me-1"></i> Ir al Panel
        </a>
    </div>
</div>

<!-- Formulario de Creación de Nuevo Administrador -->
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-custom border border-dark rounded-0">
            <div class="card-header bg-dark text-white py-3 border-bottom border-dark rounded-0">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-user-plus me-2 text-danger"></i>Crear Nuevo Usuario
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger border border-danger rounded-0 mb-4">
                        <h6 class="fw-bold mb-2 text-uppercase letter-spacing-1"><i class="fas fa-exclamation-triangle me-2"></i>Errores:</h6>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errores as $err): ?>
                                <li><?= $err; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="admins.php" novalidate>
                    <div class="mb-3">
                        <label for="nuevo_usuario" class="form-label fw-semibold">
                            <i class="fas fa-at text-muted me-1"></i>Nombre de Usuario <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nuevo_usuario" name="nuevo_usuario"
                               value="<?= e($_POST['nuevo_usuario'] ?? ''); ?>"
                               placeholder="Ej: admin2, tecnico_juan" required minlength="3"
                               pattern="[a-zA-Z0-9_]+">
                        <div class="form-text text-muted small">Solo letras, números y guiones bajos. Mínimo 3 caracteres.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nuevo_nombre" class="form-label fw-semibold">
                            <i class="fas fa-id-card text-muted me-1"></i>Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nuevo_nombre" name="nuevo_nombre"
                               value="<?= e($_POST['nuevo_nombre'] ?? ''); ?>"
                               placeholder="Ej: Juan Carlos Pérez" required minlength="3">
                    </div>

                    <div class="mb-3">
                        <label for="nuevo_rol" class="form-label fw-semibold">
                            <i class="fas fa-shield-alt text-muted me-1"></i>Rol del Usuario <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="nuevo_rol" name="nuevo_rol" required>
                            <option value="admin" <?= (($_POST['nuevo_rol'] ?? 'admin') === 'admin') ? 'selected' : ''; ?>>
                                🛡️ Administrador — Acceso total al sistema
                            </option>
                            <option value="usuario" <?= (($_POST['nuevo_rol'] ?? '') === 'usuario') ? 'selected' : ''; ?>>
                                👤 Usuario / Técnico — Acceso limitado
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nuevo_password" class="form-label fw-semibold">
                            <i class="fas fa-lock text-muted me-1"></i>Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control" id="nuevo_password" name="nuevo_password"
                               placeholder="Mínimo 6 caracteres" required minlength="6">
                    </div>

                    <div class="mb-4">
                        <label for="confirmar_password" class="form-label fw-semibold">
                            <i class="fas fa-lock text-muted me-1"></i>Confirmar Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control" id="confirmar_password" name="confirmar_password"
                               placeholder="Repita la contraseña" required minlength="6">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark btn-lg fw-bold text-uppercase letter-spacing-1 rounded-0 py-3">
                            <i class="fas fa-user-plus me-2 text-danger"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de Administradores Existentes -->
    <div class="col-lg-7">
        <div class="card card-custom border border-dark rounded-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom border-dark">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-users-cog me-2 text-primary"></i>Usuarios Registrados
                    <span class="badge bg-dark ms-2"><?= count($admins); ?></span>
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Rol</th>
                            <th>Fecha de Creación</th>
                            <th class="text-center" style="width: 100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($admins)): ?>
                            <?php foreach ($admins as $admin): ?>
                                <tr class="<?= ((int)$admin['id'] === 1) ? 'table-light' : ''; ?>">
                                    <td>
                                        <span class="font-monospace fw-bold text-muted">#<?= $admin['id']; ?></span>
                                    </td>
                                    <td>
                                        <span class="font-monospace fw-bold text-primary">@<?= e($admin['usuario']); ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= e($admin['nombre']); ?></div>
                                        <?php if ((int)$admin['id'] === 1): ?>
                                            <span class="badge bg-danger small">ADMIN PRINCIPAL</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($admin['rol'] === 'admin'): ?>
                                            <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>ADMIN</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="fas fa-user me-1"></i>USUARIO</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small text-muted"><?= formatDate($admin['creado_en']); ?></div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ((int)$admin['id'] !== 1): ?>
                                            <a href="admins.php?eliminar=<?= $admin['id']; ?>"
                                               class="btn btn-sm btn-outline-danger btn-eliminar-admin"
                                               data-nombre="<?= e($admin['nombre']); ?>"
                                               data-usuario="<?= e($admin['usuario']); ?>"
                                               title="Eliminar usuario">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border" title="No eliminable">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-3x mb-3 text-secondary opacity-50"></i>
                                    <h6>No hay administradores registrados</h6>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white py-3 text-muted small border-top">
                <i class="fas fa-info-circle me-1"></i>
                El <strong>Administrador Principal</strong> (ID #1) no puede ser eliminado por seguridad del sistema.
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
