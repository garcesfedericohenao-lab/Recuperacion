<?php
/**
 * Script de Re-importación Limpia con Codificación UTF-8 Estricta
 */

$db_host = '127.0.0.1';
$db_port = '3306';
$db_user = 'root';
$db_pass = '';

$pdo = new PDO(
    "mysql:host={$db_host};port={$db_port};charset=utf8mb4",
    $db_user,
    $db_pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]
);

$sqlFile = __DIR__ . '/schema.sql';
$sqlContent = file_get_contents($sqlFile);

if (!$sqlContent) {
    die("Error al leer schema.sql\n");
}

// Ejecutar el script SQL completo asegurando UTF-8
$pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;");
$pdo->exec($sqlContent);

echo "Base de datos re-importada con éxito con codificación UTF-8 nativa.\n";

// Verificar los registros
$pdo->exec("USE taller_reparaciones;");
$stmt = $pdo->query("SELECT id, cliente_nombre, estado, diagnostico FROM servicios;");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\n--- VERIFICACIÓN DE CARACTERES EN LA BASE DE DATOS ---\n";
foreach ($rows as $r) {
    echo "ID: {$r['id']} | Cliente: {$r['cliente_nombre']} | Estado: {$r['estado']}\n";
    echo "Diagnóstico: " . substr((string)$r['diagnostico'], 0, 70) . "...\n\n";
}
