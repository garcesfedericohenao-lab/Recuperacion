<?php
/**
 * Script de Pruebas Automatizadas y Verificación de Requisitos
 * Proyecto 4: Sistema de Seguimiento de Reparaciones Tecnológicas
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

echo "===============================================================\n";
echo " EJECUCIÓN DE PRUEBAS AUTOMATIZADAS - TALLER DE REPARACIONES\n";
echo "===============================================================\n\n";

$passed = 0;
$failed = 0;

function assertTest(string $description, bool $condition, string $detail = '') {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $description\n";
        $passed++;
    } else {
        echo " [FAIL] $description\n";
        if ($detail) {
            echo "        Detalle: $detail\n";
        }
        $failed++;
    }
}

// -------------------------------------------------------------
// Prueba 1: Conexión a Base de Datos
// -------------------------------------------------------------
assertTest(
    "1. Conexión funcional a MySQL desde PHP mediante PDO",
    $pdo instanceof PDO,
    "La instancia de PDO no está disponible."
);

// -------------------------------------------------------------
// Prueba 2: Existencia de Tablas y Esquema
// -------------------------------------------------------------
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
assertTest(
    "2. Existencia de tablas requeridas (servicios, usuarios)",
    in_array('servicios', $tables) && in_array('usuarios', $tables),
    "Tablas encontradas: " . implode(', ', $tables)
);

// -------------------------------------------------------------
// Prueba 3: Autenticación de Administrador y Usuario
// -------------------------------------------------------------
$stmtAdmin = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = 'admin' LIMIT 1");
$stmtAdmin->execute();
$adminUser = $stmtAdmin->fetch();

$adminPassValid = $adminUser && password_verify('admin123', $adminUser['password']);
assertTest(
    "3. Autenticación de Admin (usuario: admin / pass: admin123)",
    $adminPassValid && $adminUser['rol'] === 'admin',
    "Fallo en verificación de hash bcrypt para el usuario admin."
);

$stmtUser = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = 'usuario' LIMIT 1");
$stmtUser->execute();
$normalUser = $stmtUser->fetch();

$userPassValid = $normalUser && password_verify('user123', $normalUser['password']);
assertTest(
    "4. Autenticación de Usuario Técnico (usuario: usuario / pass: user123)",
    $userPassValid && $normalUser['rol'] === 'usuario',
    "Fallo en verificación de hash bcrypt para el usuario técnico."
);

// -------------------------------------------------------------
// Prueba 4: Asignación y Formato del Número de Servicio
// -------------------------------------------------------------
$nextServiceNum = generateServiceNumber($pdo);
assertTest(
    "5. Generación de número de servicio único con formato correlativo",
    (bool)preg_match('/^SRV-\d{4}-\d{4}$/', $nextServiceNum),
    "Código generado no cumple el formato esperado: $nextServiceNum"
);

// -------------------------------------------------------------
// Prueba 5: Operación CREATE (Registrar nueva orden)
// -------------------------------------------------------------
$testNum = generateServiceNumber($pdo);
$testNombre = "Cliente Prueba Automatizada";
$testTel = "3119998877";
$testTipo = "Computador de escritorio";
$testMarca = "ASUS";
$testModelo = "ROG Strix G15";
$testDano = "El equipo genera pantallazos azules (BSOD) durante carga gráfica.";

$stmtInsert = $pdo->prepare("
    INSERT INTO servicios (
        numero_servicio, cliente_nombre, cliente_telefono, tipo_dispositivo,
        marca, modelo, descripcion_dano, estado, costo_estimado, tecnico_asignado,
        fecha_ingreso
    ) VALUES (
        :num, :cliente, :tel, :tipo,
        :marca, :modelo, :dano, 'Recibido', 120000.00, 'Técnico de Soporte',
        NOW()
    )
");
$stmtInsert->execute([
    'num' => $testNum,
    'cliente' => $testNombre,
    'tel' => $testTel,
    'tipo' => $testTipo,
    'marca' => $testMarca,
    'modelo' => $testModelo,
    'dano' => $testDano
]);
$testId = (int)$pdo->lastInsertId();

assertTest(
    "6. Operación CREATE: Registro de cliente, dispositivo, marca y daño con número asignado",
    $testId > 0,
    "No se generó ID para la nueva orden."
);

// -------------------------------------------------------------
// Prueba 6: Operación READ y Búsqueda por Número de Servicio
// -------------------------------------------------------------
$stmtSearch = $pdo->prepare("SELECT * FROM servicios WHERE numero_servicio = :num LIMIT 1");
$stmtSearch->execute(['num' => $testNum]);
$foundRecord = $stmtSearch->fetch();

assertTest(
    "7. Operación READ y Búsqueda por número de servicio ($testNum)",
    $foundRecord && $foundRecord['cliente_nombre'] === $testNombre && $foundRecord['estado'] === 'Recibido',
    "No se pudo recuperar la orden recién insertada mediante búsqueda por número."
);

// -------------------------------------------------------------
// Prueba 7: VALIDACIÓN CRÍTICA - No permitir cerrar sin diagnóstico
// -------------------------------------------------------------
$blockedConstraint = false;
try {
    // Intentar actualizar a 'Cerrado' con diagnóstico vacío o null a nivel BD
    $stmtBadUpdate = $pdo->prepare("
        UPDATE servicios 
        SET estado = 'Cerrado', diagnostico = '' 
        WHERE id = :id
    ");
    $stmtBadUpdate->execute(['id' => $testId]);
} catch (PDOException $e) {
    // Debe lanzar excepción por el CHECK constraint: chk_cierre_con_diagnostico
    $blockedConstraint = true;
}

assertTest(
    "8. VALIDACIÓN CRÍTICA: Impedir cerrar la reparación si el diagnóstico está vacío",
    $blockedConstraint,
    "La base de datos permitió cerrar el servicio con diagnóstico vacío (violación de restricción no detectada)."
);

// -------------------------------------------------------------
// Prueba 8: Operación UPDATE exitosa con diagnóstico y cambio a Cerrado
// -------------------------------------------------------------
$diagText = "Se diagnosticaron módulos de RAM desfasados en frecuencia. Se configuró perfil XMP estable en BIOS y superó test de estrés de 2 horas.";
$stmtGoodUpdate = $pdo->prepare("
    UPDATE servicios 
    SET estado = 'Cerrado', diagnostico = :diag, fecha_cierre = NOW() 
    WHERE id = :id
");
$stmtGoodUpdate->execute([
    'diag' => $diagText,
    'id'   => $testId
]);

$stmtCheckUpdate = $pdo->prepare("SELECT estado, diagnostico, fecha_cierre FROM servicios WHERE id = :id");
$stmtCheckUpdate->execute(['id' => $testId]);
$updatedRecord = $stmtCheckUpdate->fetch();

assertTest(
    "9. Operación UPDATE: Actualización válida de diagnóstico y cierre de orden",
    $updatedRecord && $updatedRecord['estado'] === 'Cerrado' && !empty($updatedRecord['diagnostico']) && !empty($updatedRecord['fecha_cierre']),
    "No se actualizaron correctamente los campos de estado y diagnóstico."
);

// -------------------------------------------------------------
// Prueba 9: Operación DELETE (Eliminar registros de prueba)
// -------------------------------------------------------------
$stmtDelete = $pdo->prepare("DELETE FROM servicios WHERE id = :id");
$stmtDelete->execute(['id' => $testId]);

$stmtCheckDel = $pdo->prepare("SELECT COUNT(*) FROM servicios WHERE id = :id");
$stmtCheckDel->execute(['id' => $testId]);
$remainingCount = (int)$stmtCheckDel->fetchColumn();

assertTest(
    "10. Operación DELETE: Eliminación exitosa del registro de prueba",
    $remainingCount === 0,
    "El registro de prueba aún existe después del borrado."
);

echo "\n---------------------------------------------------------------\n";
echo " RESULTADOS FINALES: $passed APROBADAS | $failed FALLIDAS\n";
echo "---------------------------------------------------------------\n";
if ($failed === 0) {
    echo " >>> TODOS LOS REQUISITOS OBLIGATORIOS FUNCIONAN CORRECTAMENTE <<<\n";
} else {
    echo " >>> ALGUNAS PRUEBAS FALLARON, REVISAR DETALLES <<<\n";
}
echo "===============================================================\n";
