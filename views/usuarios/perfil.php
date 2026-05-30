<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="grid">
    <div class="card">
        <h1>Mi perfil</h1>
        <div class="profile">
            <p><strong>Nombre:</strong> <?= e($usuario['nombre']) ?></p>
            <p><strong>Email:</strong> <?= e($usuario['email']) ?></p>
            <p><strong>Rol:</strong> <span class="badge"><?= e($usuario['rol']) ?></span></p>
            <p><strong>Último acceso:</strong> <?= e($usuario['ultimo_acceso'] ?? 'Sin acceso') ?></p>
            <p><strong>Creado:</strong> <?= e($usuario['created_at']) ?></p>
        </div>
        <a class="btn" href="index.php?action=edit&id=<?= e($usuario['id']) ?>">Editar mi perfil</a>
    </div>

    <div class="card">
        <h2>Cambiar contraseña</h2>
        <form method="POST" action="index.php?action=change_password" class="form">
            <label>Contraseña actual</label>
            <input type="password" name="current_password" required minlength="6">

            <label>Nueva contraseña</label>
            <input type="password" name="new_password" required minlength="6">

            <label>Confirmar contraseña</label>
            <input type="password" name="confirm_password" required minlength="6">

            <button class="btn" type="submit">Actualizar contraseña</button>
        </form>
    </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
