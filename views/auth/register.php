<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="auth-card">
    <h1>Registrar usuario</h1>
    <form method="POST" action="index.php?action=register_post" class="form">
        <label>Nombre</label>
        <input type="text" name="nombre" required minlength="3" value="<?= old('nombre') ?>">

        <label>Email</label>
        <input type="email" name="email" required value="<?= old('email') ?>">

        <label>Contraseña</label>
        <input type="password" name="password" required minlength="6">

        <button class="btn" type="submit">Crear cuenta</button>
    </form>
    <p class="small">¿Ya tienes cuenta? <a href="index.php?action=login">Inicia sesión</a></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
