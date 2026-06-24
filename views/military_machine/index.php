<div class="container-fluid py-5 my-4 h-100">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Техника</h3>
                    <a href="/military-machine/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Добавить
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
                                <th>Воинская часть</th>
                                <th>Модель</th>
                                <th>Рег. номер</th>
                                <th>Топливо</th>
                                <th>Линейная норма</th>
                                <th>Дата выпуска</th>
                                <th>Порядок</th>
                                <th>Дата редактирования</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($machines as $machine): ?>
                                <tr>
                                    <td><?= $machine['id'] ?></td>
                                    <td><?= htmlspecialchars($machine['unit_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($machine['name']) ?></td>
                                    <td><?= htmlspecialchars($machine['registr_plate']) ?></td>
                                    <td><?= htmlspecialchars($machine['fuel_name'] ?? '') ?></td>
                                    <td><?= isset($machine['linear_norm']) ? number_format($machine['linear_norm'], 2, '.', '') : '—' ?></td>
                                    <td><?= isset($machine['release_date']) ? date('d.m.Y', strtotime($machine['release_date'])) : '—' ?></td>
                                    <td><?= $machine['order'] ?></td>
                                    <td><?= $machine['data_edit'] ?></td>
                                    <td>
                                        <?php if ($machine['is_active']): ?>
                                            <span class="badge bg-success">Активен</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Неактивен</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/military-machine/<?= $machine['id'] ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Просмотр">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/military-machine/edit/<?= $machine['id'] ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Редактировать">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/military-machine/delete/<?= $machine['id'] ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Вы уверены, что хотите удалить эту технику?')"
                                           data-bs-toggle="tooltip" title="Удалить">
                                            <i class="bi bi-trash"></i>
                                        </a>
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