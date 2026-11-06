<?php
/**
 * Gestión de Autenticación, Sesiones y Roles de Usuario
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario actual ha iniciado sesión.
 * @return bool
 */
function isLoggedIn(): bool {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Obtiene los datos del usuario en sesión.
 * @return array|null
 */
function currentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id'      => $_SESSION['usuario_id'],
        'usuario' => $_SESSION['usuario_nombre_usuario'] ?? '',
        'nombre'  => $_SESSION['usuario_nombre'] ?? '',
        'rol'     => $_SESSION['usuario_rol'] ?? 'usuario',
    ];
}

/**
 * Verifica si el usuario autenticado tiene rol de Administrador.
 * @return bool
 */
function isAdmin(): bool {
    return isLoggedIn() && (($_SESSION['usuario_rol'] ?? '') === 'admin');
}

/**
 * Exige inicio de sesión. Si no está autenticado, redirige al login con mensaje.
 * @param string $redirectUrl URL a donde redirigir en caso de no estar autenticado.
 */
function requireLogin(string $redirectUrl = '../login.php'): void {
    if (!isLoggedIn()) {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][] = [
            'type'    => 'warning',
            'message' => 'Debe iniciar sesión para acceder a esta sección.'
        ];
        header("Location: {$redirectUrl}");
        exit;
    }
}

/**
 * Exige rol de Administrador. Si no lo tiene, redirige al panel o muestra error.
 * @param string $redirectUrl
 */
function requireAdmin(string $redirectUrl = 'dashboard.php'): void {
    requireLogin('../login.php');
    if (!isAdmin()) {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][] = [
            'type'    => 'danger',
            'message' => 'Acceso denegado: Esta acción requiere permisos de Administrador.'
        ];
        header("Location: {$redirectUrl}");
        exit;
    }
}
