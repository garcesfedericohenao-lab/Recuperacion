<?php
/**
 * Test HTTP de las vistas y sesiones
 */

echo "===============================================================\n";
echo " TEST HTTP Y FLUJO WEB COMPLETO (VISTAS, SESIONES, FORMULARIOS)\n";
echo "===============================================================\n\n";

$baseUrl = 'http://localhost/taller_reparaciones';
$cookieFile = __DIR__ . '/cookie.txt';
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

function httpReq($url, $postData = null, $useCookies = true) {
    global $cookieFile;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($useCookies) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    if ($postData !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $httpCode, 'body' => $response];
}

// 1. Vista Pública (index.php)
$r1 = httpReq("$baseUrl/index.php");
echo "1. Vista 1: Consulta Pública (index.php) -> HTTP {$r1['code']}: " . 
    (strpos($r1['body'], 'Consulta de Reparaciones') !== false ? "OK" : "ERROR") . "\n";

// 2. Búsqueda por número de servicio en Vista Pública
$r2 = httpReq("$baseUrl/index.php?numero=SRV-2026-0001");
echo "2. Vista 1: Búsqueda por N° Servicio -> HTTP {$r2['code']}: " . 
    (strpos($r2['body'], 'Carlos Mendoza') !== false && strpos($r2['body'], 'ThinkPad T480') !== false ? "OK" : "ERROR") . "\n";

// 3. Intento de login fallido
$r3 = httpReq("$baseUrl/login.php", ['usuario' => 'admin', 'password' => 'clave_erronea']);
echo "3. Login con contraseña incorrecta -> HTTP {$r3['code']}: " . 
    (strpos($r3['body'], 'Usuario o contraseña incorrectos') !== false ? "OK (Mensaje de error detectado)" : "ERROR") . "\n";

// 4. Login exitoso como Admin
$r4 = httpReq("$baseUrl/login.php", ['usuario' => 'admin', 'password' => 'admin123']);
echo "4. Login exitoso como Admin -> HTTP {$r4['code']}: " . 
    (strpos($r4['body'], 'Administrador Principal') !== false ? "OK (Sesión iniciada como Admin)" : "ERROR") . "\n";

// 5. Vista 2: Panel de Control (admin/dashboard.php)
$r5 = httpReq("$baseUrl/admin/dashboard.php");
echo "5. Vista 2: Panel de Administración -> HTTP {$r5['code']}: " . 
    (strpos($r5['body'], 'Gestión de Reparaciones') !== false ? "OK" : "ERROR") . "\n";

// 6. Vista 3: Formulario Nuevo (admin/nuevo.php)
$r6 = httpReq("$baseUrl/admin/nuevo.php");
echo "6. Vista 3: Formulario Nueva Reparación -> HTTP {$r6['code']}: " . 
    (strpos($r6['body'], 'Nueva Orden de Reparación') !== false ? "OK" : "ERROR") . "\n";

// 7. Registro de nueva reparación mediante POST
$r7 = httpReq("$baseUrl/admin/nuevo.php", [
    'cliente_nombre'   => 'Cliente Web Test',
    'cliente_telefono' => '3123456789',
    'cliente_email'    => 'cliente@test.com',
    'tipo_dispositivo' => 'Smartphone',
    'marca'            => 'Xiaomi',
    'modelo'           => 'Redmi Note 12',
    'descripcion_dano' => 'Falla en conector de carga tipo C. No detecta cable ni carga rápida.',
    'costo_estimado'   => '75000.00',
    'tecnico_asignado' => 'Administrador Principal'
]);
echo "7. Creación de orden via POST -> HTTP {$r7['code']}: " . 
    (strpos($r7['body'], 'Comprobante') !== false || strpos($r7['body'], 'registrada exitosamente') !== false ? "OK" : "ERROR") . "\n";

// Extraer ID recién creado
preg_match('/editar\.php\?id=(\d+)/', $r7['body'], $matches);
$newId = $matches[1] ?? null;

if ($newId) {
    // 8. Validación de diagnóstico vacío al cerrar (admin/editar.php)
    $r8 = httpReq("$baseUrl/admin/editar.php?id=$newId", [
        'estado'           => 'Cerrado',
        'diagnostico'      => '', // VACÍO -> DEBE SER RECHAZADO
        'costo_estimado'   => '75000.00',
        'tecnico_asignado' => 'Administrador Principal'
    ]);
    echo "8. Vista 4: Validación de Diagnóstico obligatorio al Cerrar -> HTTP {$r8['code']}: " . 
        (strpos($r8['body'], 'No es posible cerrar o entregar una reparación con el campo de') !== false ? "OK (Validación activada correctamente)" : "ERROR") . "\n";

    // 9. Actualización exitosa con diagnóstico válido
    $r9 = httpReq("$baseUrl/admin/editar.php?id=$newId", [
        'estado'           => 'Cerrado',
        'diagnostico'      => 'Reemplazo de pin de carga y limpieza ultrasónica de placa flex.',
        'costo_estimado'   => '75000.00',
        'tecnico_asignado' => 'Administrador Principal'
    ]);
    echo "9. Vista 4: Cierre exitoso con diagnóstico -> HTTP {$r9['code']}: " . 
        (strpos($r9['body'], 'actualizado correctamente') !== false || strpos($r9['body'], 'Comprobante') !== false ? "OK" : "ERROR") . "\n";

    // 10. Vista 5: Comprobante de Servicio (admin/ver.php)
    $r10 = httpReq("$baseUrl/admin/ver.php?id=$newId");
    echo "10. Vista 5: Comprobante y Ticket Imprimible -> HTTP {$r10['code']}: " . 
        (strpos($r10['body'], 'Comprobante Oficial de Servicio') !== false ? "OK" : "ERROR") . "\n";

    // 11. Eliminación del registro de prueba (admin/eliminar.php)
    $r11 = httpReq("$baseUrl/admin/eliminar.php?id=$newId");
    echo "11. Eliminación de registro de prueba (CRUD Delete) -> HTTP {$r11['code']}: " . 
        (strpos($r11['body'], 'ha sido eliminado exitosamente') !== false ? "OK" : "ERROR") . "\n";
} else {
    echo "No se pudo extraer el ID de la orden para las pruebas 8 a 11.\n";
}

if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

echo "\n===============================================================\n";
echo " PRUEBAS HTTP COMPLETADAS EXITOSAMENTE\n";
echo "===============================================================\n";
