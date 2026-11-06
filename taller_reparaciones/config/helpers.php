<?php
/**
 * Funciones Auxiliares (Helpers)
 * Mensajes Flash, Sanitización, Formateo e Identificadores
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Escapa cadenas para prevenir ataques XSS (Cross-Site Scripting).
 * @param mixed $value
 * @return string
 */
function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Registra un mensaje Flash para la siguiente petición.
 * @param string $type success | danger | warning | info
 * @param string $message
 */
function setFlash(string $type, string $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = [
        'type'    => $type,
        'message' => $message
    ];
}

/**
 * Obtiene y limpia todos los mensajes Flash acumulados.
 * @return array
 */
function getFlashes(): array {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

/**
 * Renderiza los mensajes Flash en formato HTML con componentes de alerta Bootstrap.
 * @return string
 */
function renderFlashes(): string {
    $flashes = getFlashes();
    if (empty($flashes)) {
        return '';
    }

    $html = '<div class="flash-messages-container mb-4">';
    foreach ($flashes as $flash) {
        $type = e($flash['type']);
        $message = e($flash['message']);
        
        $icon = match ($type) {
            'success' => 'fa-check-circle',
            'danger'  => 'fa-exclamation-circle',
            'warning' => 'fa-exclamation-triangle',
            default   => 'fa-info-circle',
        };

        $html .= "
        <div class='alert alert-{$type} alert-dismissible fade show shadow-sm d-flex align-items-center' role='alert'>
            <i class='fas {$icon} me-2 fs-5'></i>
            <div>{$message}</div>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Cerrar'></button>
        </div>";
    }
    $html .= '</div>';
    return $html;
}

/**
 * Genera un código único y secuencial de número de servicio (ej: SRV-2026-0006).
 * @param PDO $pdo
 * @return string
 */
function generateServiceNumber(PDO $pdo): string {
    $year = date('Y');
    $prefix = "SRV-{$year}-";

    $stmt = $pdo->prepare("
        SELECT numero_servicio 
        FROM servicios 
        WHERE numero_servicio LIKE :prefix 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $stmt->execute(['prefix' => "{$prefix}%"]);
    $last = $stmt->fetchColumn();

    if ($last) {
        $parts = explode('-', $last);
        $lastNumber = isset($parts[2]) ? (int)$parts[2] : 0;
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }

    return sprintf("%s%04d", $prefix, $nextNumber);
}

/**
 * Renderiza un badge visual colorido para el estado del servicio.
 * @param string $estado
 * @return string
 */
function renderStatusBadge(string $estado): string {
    $estadoClean = trim($estado);
    switch ($estadoClean) {
        case 'Recibido':
            return '<span class="badge bg-dark text-white border border-dark"><i class="fas fa-inbox me-1 text-danger"></i> RECIBIDO</span>';
        case 'En diagnóstico':
            return '<span class="badge bg-light text-dark border border-dark"><i class="fas fa-microscope me-1 text-danger"></i> EN DIAGNÓSTICO</span>';
        case 'En reparación':
            return '<span class="badge bg-secondary text-white border border-secondary"><i class="fas fa-wrench me-1"></i> EN REPARACIÓN</span>';
        case 'Cerrado':
            return '<span class="badge bg-success text-white border border-success"><i class="fas fa-check-double me-1"></i> CERRADO</span>';
        default:
            return '<span class="badge bg-white text-dark border border-dark">' . e($estadoClean) . '</span>';
    }
}

/**
 * Retorna el índice de paso numérico para la barra de progreso (1 a 4).
 * @param string $estado
 * @return int
 */
function getStatusStep(string $estado): int {
    return match (trim($estado)) {
        'Recibido'       => 1,
        'En diagnóstico' => 2,
        'En reparación'  => 3,
        'Cerrado'        => 4,
        default          => 1,
    };
}

/**
 * Formatea una fecha para visualización en español.
 * @param string|null $dateStr
 * @param bool $includeTime
 * @return string
 */
function formatDate(?string $dateStr, bool $includeTime = true): string {
    if (!$dateStr) {
        return '—';
    }
    $timestamp = strtotime($dateStr);
    if (!$timestamp) {
        return '—';
    }
    $format = $includeTime ? 'd/m/Y h:i A' : 'd/m/Y';
    return date($format, $timestamp);
}

/**
 * Formatea un valor numérico como moneda (COP).
 * @param float|int|string|null $amount
 * @return string
 */
function formatMoney($amount): string {
    $val = (float)($amount ?? 0);
    return '$' . number_format($val, 2, ',', '.');
}
