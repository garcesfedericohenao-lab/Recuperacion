<?php
/**
 * Redirección permanente a index.php (nueva Zona de Recibimiento principal)
 */
$queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: index.php' . $queryString, true, 301);
exit;
