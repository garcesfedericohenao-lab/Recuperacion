<?php
/**
 * VISTA: Autenticación de Usuarios (Admin y Técnico/Usuario)
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

$basePath = '';
$pageTitle = 'Iniciar Sesión';

// Si ya tiene sesión activa, redirigir al panel
if (isLoggedIn()) {
    header('Location: admin/dashboard.php');
    exit;
}

$error = null;
$usernameVal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $usernameVal = $usuario;

    if (empty($usuario) || empty($password)) {
        $error = 'Por favor complete todos los campos de acceso.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
            $stmt->execute(['usuario' => $usuario]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Credenciales correctas: configurar variables de sesión
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nombre_usuario'] = $user['usuario'];
                $_SESSION['usuario_nombre'] = $user['nombre'];
                $_SESSION['usuario_rol'] = $user['rol'];

                setFlash('success', "¡Bienvenido de nuevo, <strong>" . e($user['nombre']) . "</strong>! Has iniciado sesión como <strong>" . strtoupper($user['rol']) . "</strong>.");
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos. Verifique sus credenciales.';
            }
        } catch (PDOException $e) {
            $error = 'Error en el servidor al intentar autenticar.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center py-5">
    <div class="col-md-6 col-lg-4">
        <div class="card card-swiss">
            <div class="card-swiss-header p-4 border-bottom text-center">
                <span class="swiss-eyebrow text-muted">AUTENTICACIÓN</span>
                <h3 class="fw-bold mb-1 letter-spacing-tight text-dark">Acceso al Sistema</h3>
                <p class="text-muted small mb-0">Servicio Técnico & Reparaciones</p>
            </div>

            <div class="card-body p-4 p-md-5">
                <?php if ($error): ?>
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                        <div><?= e($error); ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" novalidate>
                    <div class="mb-3">
                        <label for="usuario" class="form-label form-label-swiss">Nombre de Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user small"></i></span>
                            <input type="text" class="form-control border-start-0" id="usuario" name="usuario" 
                                   value="<?= e($usernameVal); ?>" placeholder="Usuario asignado" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label form-label-swiss">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock small"></i></span>
                            <input type="password" class="form-control border-start-0" id="password" name="password" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark btn-lg fw-bold rounded-1 py-3 text-uppercase letter-spacing-1">
                            Ingresar al Sistema <i class="fas fa-arrow-right ms-2 small"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer bg-white border-top text-center py-3">
                <a href="index.php" class="text-decoration-none small text-muted">
                    <i class="fas fa-arrow-left me-1"></i> Regresar al inicio
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
