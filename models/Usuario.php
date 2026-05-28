<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private PDO $db;
    private ?int $id = null;
    private string $nombre = '';
    private string $email = '';
    private string $password = '';
    private string $rol = 'user';
    private bool $activo = true;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNombre(): string { return $this->nombre; }
    public function setNombre(string $nombre): void { $this->nombre = trim($nombre); }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = trim(strtolower($email)); }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getRol(): string { return $this->rol; }
    public function setRol(string $rol): void { $this->rol = in_array($rol, ['admin', 'user'], true) ? $rol : 'user'; }

    public function getActivo(): bool { return $this->activo; }
    public function setActivo(bool $activo): void { $this->activo = $activo; }

    public function getAll(?string $search = null, ?string $role = null): array {
        $sql = "SELECT id, nombre, email, rol, activo, ultimo_acceso, created_at, updated_at FROM usuarios WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (nombre LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        if ($role && in_array($role, ['admin', 'user'], true)) {
            $sql .= " AND rol = :rol";
            $params[':rol'] = $role;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol, activo, ultimo_acceso, created_at, updated_at FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function getByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email AND activo = 1 LIMIT 1");
        $stmt->execute([':email' => trim(strtolower($email))]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function create(): bool {
        $errores = $this->validate(true);
        if (!empty($errores)) {
            throw new InvalidArgumentException(implode(' ', $errores));
        }
        if ($this->emailExists($this->email)) {
            throw new InvalidArgumentException('El email ya está registrado.');
        }

        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (:nombre, :email, :password, :rol, :activo)");
        return $stmt->execute([
            ':nombre' => $this->nombre,
            ':email' => $this->email,
            ':password' => password_hash($this->password, PASSWORD_DEFAULT),
            ':rol' => $this->rol,
            ':activo' => $this->activo ? 1 : 0,
        ]);
    }

    public function update(): bool {
        if ($this->id === null) {
            throw new InvalidArgumentException('ID de usuario no válido.');
        }
        $errores = $this->validate(false);
        if (!empty($errores)) {
            throw new InvalidArgumentException(implode(' ', $errores));
        }
        if ($this->emailExists($this->email, $this->id)) {
            throw new InvalidArgumentException('El email ya está registrado por otro usuario.');
        }

        $stmt = $this->db->prepare("UPDATE usuarios SET nombre = :nombre, email = :email, rol = :rol, activo = :activo WHERE id = :id");
        return $stmt->execute([
            ':nombre' => $this->nombre,
            ':email' => $this->email,
            ':rol' => $this->rol,
            ':activo' => $this->activo ? 1 : 0,
            ':id' => $this->id,
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        if (strlen($newPassword) < 6) {
            throw new InvalidArgumentException('La contraseña debe tener mínimo 6 caracteres.');
        }
        $stmt = $this->db->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
        return $stmt->execute([
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE usuarios SET activo = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function updateLastAccess(int $id): bool {
        $stmt = $this->db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function validate(bool $requirePassword = false): array {
        $errores = [];
        if (mb_strlen($this->nombre) < 3) {
            $errores[] = 'El nombre debe tener mínimo 3 caracteres.';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es válido.';
        }
        if (!in_array($this->rol, ['admin', 'user'], true)) {
            $errores[] = 'El rol no es válido.';
        }
        if ($requirePassword && strlen($this->password) < 6) {
            $errores[] = 'La contraseña debe tener mínimo 6 caracteres.';
        }
        return $errores;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $params = [':email' => trim(strtolower($email))];
        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }
}
