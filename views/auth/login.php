<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="auth-card">
    <h1>Iniciar sesión</h1>
    <p class="muted">Accede con tu email y contraseña.</p>
    <form method="POST" action="index.php?action=login_post" class="form">
        <label>Email</label>
        <input type="email" name="email" required placeholder="admin@sistema.com">

        <label>Contraseña</label>
        <input type="password" name="password" required placeholder="password">

        <button class="btn" type="submit">Ingresar</button>
    </form>
    <p class="small">¿No tienes cuenta? <a href="index.php?action=register">Regístrate</a></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
