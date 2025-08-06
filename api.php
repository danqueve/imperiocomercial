<?php
// api.php
// Establece la cabecera para indicar que la respuesta será en formato JSON.
header('Content-Type: application/json');

// 1. INCLUIR ARCHIVOS DE CONFIGURACIÓN Y FUNCIONES
require_once 'lib/config.php';
require_once 'lib/functions.php';

// 2. VERIFICAR QUE EL USUARIO HAYA INICIADO SESIÓN
// Si no hay sesión activa, la función check_login() redirigiría,
// pero como es una API, es mejor terminar y dar un error JSON.
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado. Debes iniciar sesión.']);
    exit;
}

// 3. VERIFICAR QUE EL USUARIO TENGA PERMISOS (NO SEA VENDEDOR)
if ($_SESSION['user_rol'] === 'vendedor') {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para realizar esta acción.']);
    exit;
}

// 4. LEER LOS DATOS ENVIADOS DESDE JAVASCRIPT
// Los datos vienen en formato JSON, no en el típico $_POST.
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? null;

if (!$action) {
    echo json_encode(['success' => false, 'message' => 'No se especificó ninguna acción.']);
    exit;
}

// 5. PROCESAR LA ACCIÓN SOLICITADA
try {
    // Se utiliza un switch para manejar las diferentes acciones posibles.
    switch ($action) {
        
        // CASO 1: CAMBIAR EL ROL DE UN USUARIO
        case 'change_role':
            // Solo el superusuario puede cambiar roles.
            if ($_SESSION['user_rol'] !== 'superusuario') {
                throw new Exception('No tienes los permisos necesarios para cambiar roles.');
            }
            // Validar los datos recibidos.
            $user_id = filter_var($data['user_id'], FILTER_VALIDATE_INT);
            $new_role = in_array($data['new_role'], ['vendedor', 'supervisor']) ? $data['new_role'] : null;

            if (!$user_id || !$new_role) {
                throw new Exception('Datos inválidos para cambiar el rol.');
            }
            
            // Preparar y ejecutar la consulta SQL.
            $stmt = $pdo->prepare("UPDATE usuarios SET rol = ? WHERE id = ? AND rol != 'superusuario'");
            $stmt->execute([$new_role, $user_id]);
            echo json_encode(['success' => true, 'message' => 'Rol actualizado correctamente.']);
            break;

        // CASO 2: ACTUALIZAR EL ESTADO DE UN FORMULARIO
        case 'update_status':
            // Validar los datos recibidos.
            $form_id = filter_var($data['form_id'], FILTER_VALIDATE_INT);
            $status = in_array($data['status'], ['aprobado', 'rechazado']) ? $data['status'] : null;
            // El motivo del rechazo se sanea para evitar inyección de código.
            $reason = ($status === 'rechazado') ? filter_var($data['reason'], FILTER_SANITIZE_STRING) : null;

            if (!$form_id || !$status) {
                throw new Exception('Datos inválidos para actualizar el estado.');
            }

            // Preparar y ejecutar la consulta SQL.
            $sql = "UPDATE formularios SET estado = ?, motivo_rechazo = ?, supervisor_id_accion = ?, fecha_actualizacion_estado = NOW() WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$status, $reason, $_SESSION['user_id'], $form_id]);
            echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
            break;

        // CASO POR DEFECTO: SI LA ACCIÓN NO SE RECONOCE
        default:
            throw new Exception('La acción solicitada no es válida.');
    }
} catch (Exception $e) {
    // Si ocurre cualquier error durante el proceso, se captura y se devuelve un mensaje.
    // El código de estado HTTP 400 indica una "mala petición".
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
