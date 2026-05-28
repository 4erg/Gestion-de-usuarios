<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="card narrow">
    <h1>Editar usuario</h1>
    <form method="POST" action="index.php?action=update&id=<?= e($usuario['id']) ?>" class="form">
        <label>Nombre</label>
        <input type="text" name="nombre" required minlength="3" value="<?= e($usuario['nombre']) ?>">

        <label>Email</label>
        <input type="email" name="email" required value="<?= e($usuario['email']) ?>">

        <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
            <label>Rol</label>
            <select name="rol">
                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="user" <?= $usuario['rol'] === 'user' ? 'selected' : '' ?>>Usuario</option>
            </select>

            <label class="checkbox">
                <input type="checkbox" name="activo" <?= $usuario['activo'] ? 'checked' : '' ?>> Usuario activo
            </label>
        <?php endif; ?>

        <div class="row-actions">
            <button class="btn" type="submit">Guardar cambios</button>
            <a class="btn secondary" href="index.php?action=dashboard">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
