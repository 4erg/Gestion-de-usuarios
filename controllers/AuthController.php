<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private Auth $auth;

    public function __construct() {
        $this->auth = new Auth();
    }

    public function showLogin(): void {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->login($email, $password)) {
            setFlash('success', 'Inicio de sesión correcto.');
            redirect('dashboard');
        }

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

            setFlash('success', 'Usuario registrado. Ahora puedes iniciar sesión.');
            redirect('login');
        } catch (Throwable $e) {
            setFlash('error', $e->getMessage());
            redirect('register');
        }
    }
}
