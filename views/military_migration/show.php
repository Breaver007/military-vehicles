<div class="container-fluid py-5 my-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Просмотр миграции</h3>
                    <div>
                        <?php if (!$migration['is_executed']): ?>
                            <a href="/military-migration/execute/<?= $migration['id'] ?>"
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Выполнить SQL-запрос?')">
                                <i class="bi bi-play-fill"></i> Выполнить
                            </a>
                        <?php endif; ?>
                        <a href="/military-migration" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td><?= $migration['id'] ?></td>
                        </tr>
                        <tr>
                            <th>Название</th>
                            <td><?= htmlspecialchars($migration['name']) ?></td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>
                                <?php if ($migration['is_executed']): ?>
                                    <span class="badge bg-success">Выполнена</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Ожидает</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Создана</th>
                            <td><?= $migration['created_at'] ?></td>
                        </tr>
                        <tr>
                            <th>Выполнена</th>
                            <td><?= $migration['executed_at'] ?? '—' ?></td>
                        </tr>
                    </table>

                    <h5 class="mt-4">SQL-запрос</h5>
                    <pre class="bg-dark text-light p-3 rounded" style="font-size: 0.85rem; max-height: 400px; overflow-y: auto;"><code><?= htmlspecialchars($migration['sql']) ?></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
