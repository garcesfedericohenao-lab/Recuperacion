<?php
require_once __DIR__ . '/../config/db.php';

$hashAdmin = password_hash('admin123', PASSWORD_BCRYPT);
$hashUser  = password_hash('user123', PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE usuarios SET password = :pass WHERE usuario = :user");
$stmt->execute(['pass' => $hashAdmin, 'user' => 'admin']);
$stmt->execute(['pass' => $hashUser, 'user' => 'usuario']);

echo "Passwords updated successfully:\n";
echo "admin: admin123 -> $hashAdmin\n";
echo "usuario: user123 -> $hashUser\n";
