<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Log.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class UsuarioController {
    private Usuario $usuarioModel;
    private Log $logModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->logModel = new Log();
    }

    public function dashboard(): void {
        AuthMiddleware::check();
        $auth = new Auth();
        if ($auth->hasRole('admin')) {
            redirect('usuarios');
        }
        redirect('perfil');
    }

    public function index(): void {
        AuthMiddleware::adminOnly();
        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['rol'] ?? '');
        $usuarios = $this->usuarioModel->getAll($search ?: null, $role ?: null);
        require __DIR__ . '/../views/usuarios/index.php';
    }

    public function logs(): void {
        AuthMiddleware::adminOnly();
        $logs = $this->logModel->getAll(300);
        require __DIR__ . '/../views/logs/index.php';
    }

    public function edit(int $id): void {
        AuthMiddleware::check();
        $current = $_SESSION['user'];
        if ($current['rol'] !== 'admin' && (int)$current['id'] !== $id) {
            http_response_code(403);
            setFlash('error', 'Solo puedes editar tu propio perfil.');
            redirect('perfil');
        }
        $usuario = $this->usuarioModel->getById($id);
        if (!$usuario) {
            setFlash('error', 'Usuario no encontrado.');
            redirect('dashboard');
        }
        require __DIR__ . '/../views/usuarios/edit.php';
    }

    public function update(int $id): void {
        AuthMiddleware::check();
        $current = $_SESSION['user'];
        if ($current['rol'] !== 'admin' && (int)$current['id'] !== $id) {
            setFlash('error', 'No tienes permisos para actualizar este usuario.');
            redirect('perfil');
        }

        try {
            $usuarioActual = $this->usuarioModel->getById($id);
            if (!$usuarioActual) {
                throw new InvalidArgumentException('Usuario no encontrado.');
            }

            $usuario = new Usuario();
            $usuario->setId($id);
            $usuario->setNombre($_POST['nombre'] ?? '');
            $usuario->setEmail($_POST['email'] ?? '');

            if ($current['rol'] === 'admin') {
                $usuario->setRol($_POST['rol'] ?? 'user');
                $usuario->setActivo(isset($_POST['activo']));
            } else {
                $usuario->setRol($usuarioActual['rol']);
                $usuario->setActivo((bool)$usuarioActual['activo']);
            }

            $usuario->update();

            if ((int)$current['id'] === $id) {
                $_SESSION['user']['nombre'] = $_POST['nombre'];
                $_SESSION['user']['email'] = $_POST['email'];
            }

            $this->safeLog(
                'usuario_editado',
                (int)$current['id'],
                $current['email'] ?? null,
                $id,
                'Actualizacion de datos de usuario'
            );

            setFlash('success', 'Usuario actualizado correctamente.');
            redirect($current['rol'] === 'admin' ? 'usuarios' : 'perfil');
        } catch (Throwable $e) {
            setFlash('error', $e->getMessage());
            header('Location: index.php?action=edit&id=' . $id);
            exit;
        }
    }

    public function delete(int $id): void {
        AuthMiddleware::adminOnly();
        if ((int)$_SESSION['user']['id'] === $id) {
            setFlash('error', 'No puedes eliminar tu propia cuenta desde aquí.');
            redirect('usuarios');
        }
        $this->usuarioModel->delete($id);
        $this->safeLog(
            'usuario_eliminado',
            (int)$_SESSION['user']['id'],
            $_SESSION['user']['email'] ?? null,
            $id,
            'Desactivacion logica de usuario'
        );
        setFlash('success', 'Usuario desactivado correctamente.');
        redirect('usuarios');
    }

    public function perfil(): void {
        AuthMiddleware::check();
        $usuario = $this->usuarioModel->getById((int)$_SESSION['user']['id']);
        require __DIR__ . '/../views/usuarios/perfil.php';
    }

    public function changePassword(): void {
        AuthMiddleware::check();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $userId = (int)$_SESSION['user']['id'];

        if (!$this->usuarioModel->verifyPassword($userId, $currentPassword)) {
            setFlash('error', 'La contraseña actual es incorrecta.');
            redirect('perfil');
        }

        if ($newPassword !== $confirmPassword) {
            setFlash('error', 'Las contraseñas no coinciden.');
            redirect('perfil');
        }

        try {
            $this->usuarioModel->updatePassword($userId, $newPassword);
            setFlash('success', 'Contraseña actualizada correctamente.');
        } catch (Throwable $e) {
            setFlash('error', $e->getMessage());
        }
        redirect('perfil');
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
