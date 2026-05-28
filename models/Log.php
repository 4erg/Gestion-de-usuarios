<?php
require_once __DIR__ . '/../config/Database.php';

class Log {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(
        string $evento,
        ?int $usuarioId,
        ?string $usuarioEmail,
        ?int $objetivoUsuarioId,
        ?string $ip,
        ?string $detalle = null
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO logs (evento, usuario_id, usuario_email, objetivo_usuario_id, ip, detalle)
             VALUES (:evento, :usuario_id, :usuario_email, :objetivo_usuario_id, :ip, :detalle)"
        );

        return $stmt->execute([
            ':evento' => $evento,
            ':usuario_id' => $usuarioId,
            ':usuario_email' => $usuarioEmail,
            ':objetivo_usuario_id' => $objetivoUsuarioId,
            ':ip' => $ip,
            ':detalle' => $detalle,
        ]);
    }

    public function getAll(int $limit = 300): array {
        $limit = max(1, min($limit, 1000));
        $stmt = $this->db->prepare(
            "SELECT id, evento, usuario_id, usuario_email, objetivo_usuario_id, ip, detalle, created_at
             FROM logs
             ORDER BY id DESC
             LIMIT :lim"
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
