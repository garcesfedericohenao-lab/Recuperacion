<?php
/**
 * Configuración de la Base de Datos y Conexión PDO
 * Taller de Reparaciones Tecnológicas
 */

$db_host = '127.0.0.1';
$db_port = '3306';
$db_name = 'taller_reparaciones';
$db_user = 'root';
$db_pass = ''; // Por defecto en XAMPP suele ser vacío

try {
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ];

    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // Si la conexión falla, mostramos un mensaje amigable y descriptivo
    die("
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24; border-radius: 8px;'>
            <h2 style='margin-top:0;'>⚠️ Error de Conexión a la Base de Datos</h2>
            <p>No se pudo establecer conexión con MySQL:</p>
            <pre style='background: #fff; padding: 10px; border-radius: 4px; border: 1px solid #ddd;'>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</pre>
            <p><strong>Verifique:</strong></p>
            <ul>
                <li>Que el servicio MySQL esté iniciado en el Panel de Control de XAMPP.</li>
                <li>Que la base de datos <code>{$db_name}</code> exista (importe el archivo <code>database/schema.sql</code>).</li>
                <li>Que el usuario y contraseña en <code>config/db.php</code> sean correctos.</li>
            </ul>
        </div>
    ");
}
