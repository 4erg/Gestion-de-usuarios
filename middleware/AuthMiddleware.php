<?php
require_once __DIR__ . '/../models/Auth.php';

class AuthMiddleware {
    public static function check(): void {
        $auth = new Auth();
        if (!$auth->isLoggedIn()) {
            setFlash('error', 'Debes iniciar sesión para continuar.');
            redirect('login');
        }
    }

    public static function adminOnly(): void {
        self::check();
        $auth = new Auth();
        if (!$auth->hasRole('admin')) {
            http_response_code(403);
            require __DIR__ . '/../views/layout/header.php';
            echo '<div class="card"><h2>Error 403</h2><p>No tienes permisos para acceder a esta sección.</p><a class="btn" href="index.php?action=perfil">Volver a mi perfil</a></div>';
            require __DIR__ . '/../views/layout/footer.php';
            exit;
        }
    }
}
