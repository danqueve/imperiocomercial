<?php
// lib/functions.php
require_once 'config.php';

/**
 * Redirige a una página específica.
 * @param string $url La URL a la que redirigir.
 */
function redirect(string $url): void {
    header("Location: {$url}");
    exit();
}

/**
 * Verifica si un usuario ha iniciado sesión.
 * Si no, lo redirige a la página de login.
 */
function check_login(): void {
    if (!isset($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

/**
 * Obtiene los datos de un usuario por su ID.
 * @param PDO $pdo Conexión a la base de datos.
 * @param int $id ID del usuario.
 * @return array|false
 */
function get_user_by_id(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtiene las estadísticas de formularios para un vendedor.
 * @param PDO $pdo Conexión a la base de datos.
 * @param int $vendedor_id ID del vendedor.
 * @return array
 */
function get_vendedor_stats(PDO $pdo, int $vendedor_id): array {
    $estados = ['aprobado', 'rechazado', 'en revision'];
    $stats = [];
    foreach ($estados as $estado) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM formularios WHERE vendedor_id = ? AND estado = ?");
        $stmt->execute([$vendedor_id, $estado]);
        $stats[$estado] = $stmt->fetchColumn();
    }
    return $stats;
}

/**
 * Obtiene todos los formularios de un vendedor.
 * @param PDO $pdo Conexión a la base de datos.
 * @param int $vendedor_id ID del vendedor.
 * @return array
 */
function get_formularios_by_vendedor(PDO $pdo, int $vendedor_id): array {
    $stmt = $pdo->prepare("SELECT * FROM formularios WHERE vendedor_id = ? ORDER BY fecha_creacion DESC");
    $stmt->execute([$vendedor_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene todos los formularios para un supervisor o superusuario.
 * @param PDO $pdo Conexión a la base de datos.
 * @return array
 */
function get_all_formularios(PDO $pdo): array {
    $sql = "
        SELECT f.*, CONCAT(u.nombre, ' ', u.apellido) AS vendedor_nombre
        FROM formularios f
        JOIN usuarios u ON f.vendedor_id = u.id
        ORDER BY f.fecha_creacion DESC
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene el log de actividad de los formularios.
 * @param PDO $pdo Conexión a la base de datos.
 * @return array
 */
function get_activity_log(PDO $pdo): array {
    $sql = "
        SELECT
            f.id,
            f.cliente_apellido_nombre,
            f.estado,
            f.fecha_actualizacion_estado,
            CONCAT(u.nombre, ' ', u.apellido) AS supervisor_nombre
        FROM formularios f
        JOIN usuarios u ON f.supervisor_id_accion = u.id
        WHERE f.fecha_actualizacion_estado IS NOT NULL
        ORDER BY f.fecha_actualizacion_estado DESC
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene todos los usuarios (para el superusuario).
 * @param PDO $pdo Conexión a la base de datos.
 * @return array
 */
function get_all_users(PDO $pdo): array {
    $stmt = $pdo->query("SELECT id, email, dni, apellido, nombre, rol FROM usuarios WHERE rol != 'superusuario' ORDER BY apellido, nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
