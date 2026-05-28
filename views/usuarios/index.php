<?php
$search = $search ?? '';
$role = $role ?? '';
$usuarios = $usuarios ?? [];
?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="card">
    <div class="page-header">
        <div>
            <h1>Gestión de usuarios</h1>
            <p>Listado completo disponible solo para administradores.</p>
        </div>

        <div class="toolbar-actions">
            <a class="btn btn-primary" href="index.php?action=register">
                + Nuevo usuario
            </a>
        </div>
    </div>

    <form class="filters" method="GET" action="index.php">
        <input type="hidden" name="action" value="usuarios">

        <input
            type="text"
            name="search"
            placeholder="Buscar por nombre o email"
            value="<?= htmlspecialchars($search) ?>"
        >

        <select name="rol">
            <option value="">Todos los roles</option>
            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>
                Admin
            </option>
            <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>
                Usuario
            </option>
        </select>

        <button class="btn secondary" type="submit">
            Filtrar
        </button>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['id']) ?></td>

                            <td><?= htmlspecialchars($u['nombre']) ?></td>

                            <td><?= htmlspecialchars($u['email']) ?></td>

                            <td>
                                <span class="badge">
                                    <?= htmlspecialchars($u['rol']) ?>
                                </span>
                            </td>

                            <td>
                                <?php if (!empty($u['activo'])): ?>
                                    <span class="ok">Activo</span>
                                <?php else: ?>
                                    <span class="bad">Inactivo</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= !empty($u['ultimo_acceso'])
                                    ? htmlspecialchars($u['ultimo_acceso'])
                                    : 'Sin acceso'
                                ?>
                            </td>

                            <td>
                                <?= !empty($u['created_at'])
                                    ? htmlspecialchars($u['created_at'])
                                    : '-'
                                ?>
                            </td>

                            <td class="actions">
                                <a
                                    class="btn small"
                                    href="index.php?action=edit&id=<?= urlencode($u['id']) ?>"
                                >
                                    Editar
                                </a>

                                <?php if ((int)$u['id'] !== (int)($_SESSION['user_id'] ?? 0)): ?>
                                    <a
                                        class="btn small danger"
                                        href="index.php?action=delete&id=<?= urlencode($u['id']) ?>"
                                        onclick="return confirm('¿Seguro que deseas eliminar este usuario?')"
                                    >
                                        Eliminar
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty">
                            No se encontraron usuarios.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
