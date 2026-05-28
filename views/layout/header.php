<?php $flash = getFlash(); $currentUser = $_SESSION['user'] ?? null; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Usuarios</title>
    <link rel="stylesheet" href="/gestion-usuarios/assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="brand">Usuarios MVC</div>
    <nav>
        <?php if ($currentUser): ?>
            <?php if ($currentUser['rol'] === 'admin'): ?>
                <a href="index.php?action=usuarios">Usuarios</a>
                <a href="index.php?action=logs">Logs</a>
            <?php endif; ?>
            <a href="index.php?action=perfil">Mi perfil</a>
            <a href="index.php?action=logout">Salir</a>
        <?php else: ?>
            <a href="index.php?action=login">Login</a>
            <a href="index.php?action=register">Registro</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">
    <?php if ($flash): ?>
        <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
