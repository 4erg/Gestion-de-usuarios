<?php require __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <div class="page-header">
        <div>
            <h1>Logs del sistema</h1>
            <p>Vista exclusiva para administradores. Se muestran los ultimos registros.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Evento</th>
                    <th>Usuario</th>
                    <th>Objetivo</th>
                    <th>IP</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= e((string)$log['id']) ?></td>
                            <td><?= e($log['created_at']) ?></td>
                            <td><span class="badge"><?= e($log['evento']) ?></span></td>
                            <td>
                                <?= !empty($log['usuario_id']) ? e((string)$log['usuario_id']) : '-' ?>
                                <?= !empty($log['usuario_email']) ? ' / ' . e($log['usuario_email']) : '' ?>
                            </td>
                            <td><?= !empty($log['objetivo_usuario_id']) ? e((string)$log['objetivo_usuario_id']) : '-' ?></td>
                            <td><?= e($log['ip'] ?? '-') ?></td>
                            <td><?= e($log['detalle'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty">No hay logs registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
