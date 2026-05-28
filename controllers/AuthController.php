<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Log.php';

class AuthController {
    private Auth $auth;
    private Log $logModel;

    public function __construct() {
        $this->auth = new Auth();
        $this->logModel = new Log();
    }

    public function showLogin(): void {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->login($email, $password)) {
            $user = $_SESSION['user'] ?? null;
            $this->safeLog(
                'login_exitoso',
                isset($user['id']) ? (int)$user['id'] : null,
                $user['email'] ?? trim(strtolower($email)),
                isset($user['id']) ? (int)$user['id'] : null,
                'Inicio de sesion correcto'
            );
            setFlash('success', 'Inicio de sesión correcto.');
            redirect('dashboard');
        }

        $this->safeLog(
            'login_fallido',
            null,
            trim(strtolower($email)),
            null,
            'Credenciales invalidas o usuario inactivo'
        );
        setFlash('error', 'Email o contraseña incorrectos, o usuario inactivo.');
        redirect('login');
    }

    public function logout(): void {
        $this->auth->logout();
        session_start();
        setFlash('success', 'Sesión cerrada correctamente.');
        redirect('login');
    }

    public function showRegister(): void {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void {
        try {
            $usuario = new Usuario();
            $usuario->setNombre($_POST['nombre'] ?? '');
            $usuario->setEmail($_POST['email'] ?? '');
            $usuario->setPassword($_POST['password'] ?? '');
            $usuario->setRol('user');
            $usuario->setActivo(true);
            $usuario->create();

            $nuevoUsuario = $usuario->getByEmail($_POST['email'] ?? '');
            $this->safeLog(
                'usuario_creado',
                isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null,
                $_SESSION['user']['email'] ?? ($nuevoUsuario['email'] ?? null),
                isset($nuevoUsuario['id']) ? (int)$nuevoUsuario['id'] : null,
                'Registro de usuario desde formulario publico'
            );

            setFlash('success', 'Usuario registrado. Ahora puedes iniciar sesión.');
            redirect('login');
        } catch (Throwable $e) {
            setFlash('error', $e->getMessage());
            redirect('register');
        }
    }

    private function safeLog(
        string $evento,
        ?int $usuarioId,
        ?string $usuarioEmail,
        ?int $objetivoUsuarioId,
        ?string $detalle = null
    ): void {
        try {
            $this->logModel->create(
                $evento,
                $usuarioId,
                $usuarioEmail,
                $objetivoUsuarioId,
                clientIp(),
                $detalle
            );
        } catch (Throwable $e) {
            // Evita romper el flujo principal por fallos de logging.
        }
    }
}
