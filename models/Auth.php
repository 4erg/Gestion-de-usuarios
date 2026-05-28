<?php
require_once __DIR__ . '/Usuario.php';

class Auth {
    private Usuario $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login(string $email, string $password): bool {
        $usuario = $this->usuarioModel->getByEmail($email);
        if (!$usuario || !password_verify($password, $usuario['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol'],
        ];
        $this->usuarioModel->updateLastAccess((int)$usuario['id']);
        return true;
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user']['id']);
    }

    public function getCurrentUser(): ?array {
        return $_SESSION['user'] ?? null;
    }

    public function hasRole(string $role): bool {
        return isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] === $role;
    }
}
