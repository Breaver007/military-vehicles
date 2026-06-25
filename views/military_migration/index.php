<div class="container-fluid py-5 my-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Миграции SQL</h3>
                    <a href="/military-migration/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Новая миграция
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Статус</th>
                                <th>Создана</th>
                                <th>Выполнена</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($migrations as $m): ?>
                                <tr>
                                    <td><?= $m['id'] ?></td>
                                    <td><?= htmlspecialchars($m['name']) ?></td>
                                    <td>
                                        <?php if ($m['is_executed']): ?>
                                            <span class="badge bg-success">Выполнена</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Ожидает</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $m['created_at'] ?></td>
                                    <td><?= $m['executed_at'] ?? '—' ?></td>
                                    <td>
                                        <a href="/military-migration/<?= $m['id'] ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Просмотр">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if (!$m['is_executed']): ?>
                                            <a href="/military-migration/execute/<?= $m['id'] ?>"
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Выполнить SQL-запрос?\n<?= htmlspecialchars($m['sql']) ?>')"
                                               data-bs-toggle="tooltip" title="Выполнить">
                                                <i class="bi bi-play-fill"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="/military-migration/delete/<?= $m['id'] ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Удалить миграцию?')"
                                           data-bs-toggle="tooltip" title="Удалить">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($migrations)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Нет миграций</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
