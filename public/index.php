<?php
session_start();

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';

$action = $_GET['action'] ?? 'dashboard';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$authController = new AuthController();
$usuarioController = new UsuarioController();

try {
    switch ($action) {
        case 'login':
            $authController->showLogin();
            break;
        case 'login_post':
            if ($method !== 'POST') { redirect('login'); }
            $authController->login();
            break;
        case 'logout':
            $authController->logout();
            break;
        case 'register':
            $authController->showRegister();
            break;
        case 'register_post':
            if ($method !== 'POST') { redirect('register'); }
            $authController->register();
            break;
        case 'dashboard':
            $usuarioController->dashboard();
            break;
        case 'usuarios':
            $usuarioController->index();
            break;
        case 'edit':
            if ($id <= 0) { redirect('dashboard'); }
            $usuarioController->edit($id);
            break;
        case 'update':
            if ($method !== 'POST' || $id <= 0) { redirect('dashboard'); }
            $usuarioController->update($id);
            break;
        case 'delete':
            if ($id <= 0) { redirect('usuarios'); }
            $usuarioController->delete($id);
            break;
        case 'perfil':
            $usuarioController->perfil();
            break;
        case 'change_password':
            if ($method !== 'POST') { redirect('perfil'); }
            $usuarioController->changePassword();
            break;
        default:
            http_response_code(404);
            require __DIR__ . '/../views/layout/header.php';
            echo '<div class="card"><h1>404</h1><p>Página no encontrada.</p><a class="btn" href="index.php?action=dashboard">Volver</a></div>';
            require __DIR__ . '/../views/layout/footer.php';
            break;
    }
} catch (Throwable $e) {
    http_response_code(500);
    require __DIR__ . '/../views/layout/header.php';
    echo '<div class="card"><h1>Error interno</h1><p>' . e($e->getMessage()) . '</p></div>';
    require __DIR__ . '/../views/layout/footer.php';
}
