<?php
/**
 * Cierre de Sesión Seguro
 */
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

// Limpiar sesión
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

// Iniciar nueva sesión para mensaje flash
session_start();
setFlash('info', 'Has cerrado tu sesión de forma segura.');
header('Location: login.php');
exit;
