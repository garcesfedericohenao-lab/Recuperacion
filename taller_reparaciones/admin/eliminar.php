<?php
/**
 * Eliminación de Registros de Prueba (CRUD - Delete)
 * Solo permitido para rol 'admin'.
 */

$basePath = '../';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

// Exige que el usuario sea Administrador
requireAdmin('dashboard.php');

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('danger', 'Identificador de registro no válido.');
    header('Location: dashboard.php');
    exit;
}

try {
    // Verificar si el registro existe antes de eliminarlo
    $stmtCheck = $pdo->prepare("SELECT numero_servicio, cliente_nombre FROM servicios WHERE id = :id LIMIT 1");
    $stmtCheck->execute(['id' => $id]);
    $servicio = $stmtCheck->fetch();

    if (!$servicio) {
        setFlash('warning', 'El registro que intenta eliminar no existe o ya fue removido.');
        header('Location: dashboard.php');
        exit;
    }

    // Proceder con la eliminación
    $stmtDelete = $pdo->prepare("DELETE FROM servicios WHERE id = :id");
    $stmtDelete->execute(['id' => $id]);

    setFlash('success', "El registro de prueba con número de servicio <strong>{$servicio['numero_servicio']}</strong> ({$servicio['cliente_nombre']}) ha sido eliminado exitosamente.");

} catch (PDOException $e) {
    setFlash('danger', 'Error al eliminar el registro en la base de datos: ' . $e->getMessage());
}

header('Location: dashboard.php');
exit;
