<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Записи антифриза</h3>
                    <a href="/military-antifreeze/records/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Добавить запись
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show"><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Антифриз</th>
                                <th>Техника</th>
                                <th>Количество</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($records as $r): ?>
                                <tr>
                                    <td><?= $r['id'] ?></td>
                                    <td><?= $r['date'] ?></td>
                                    <td><?= htmlspecialchars($antifreezeMap[$r['mt_antifreeze_id']]['name'] ?? '?') ?></td>
                                    <td><?= htmlspecialchars($machineMap[$r['machine_id']]['name'] ?? '?') ?></td>
                                    <td><?= $r['value'] ?></td>
                                    <td>
                                        <a href="/military-antifreeze/records/edit/<?= $r['id'] ?>" class="btn btn-sm btn-warning" title="Редактировать"><i class="bi bi-pencil"></i></a>
                                        <a href="/military-antifreeze/records/delete/<?= $r['id'] ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Вы уверены?')" title="Удалить"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
