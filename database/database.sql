CREATE DATABASE IF NOT EXISTS usuarios_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE usuarios_mvc;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin', 'user') DEFAULT 'user',
  activo BOOLEAN DEFAULT TRUE,
  ultimo_acceso DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evento VARCHAR(100) NOT NULL,
  usuario_id INT NULL,
  usuario_email VARCHAR(100) NULL,
  objetivo_usuario_id INT NULL,
  ip VARCHAR(45) NULL,
  detalle TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_evento (evento),
  INDEX idx_created_at (created_at),
  INDEX idx_usuario_id (usuario_id),
  INDEX idx_objetivo_usuario_id (objetivo_usuario_id)
);

INSERT INTO usuarios (nombre, email, password, rol, activo)
VALUES ('Administrador', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1)
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO usuarios (nombre, email, password, rol, activo)
VALUES ('Usuario de prueba', 'usuario@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1)
ON DUPLICATE KEY UPDATE email = email;
