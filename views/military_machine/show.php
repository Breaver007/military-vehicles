<div class="container-fluid py-5 my-4 vh-100">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Просмотр техники</h3>
                    <div>
                        <a href="/military-machine/edit/<?= $machine['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                        <a href="/military-machine" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td><?= $machine['id'] ?></td>
                        </tr>
                        <tr>
                            <th>Воинская часть</th>
                            <td><?= htmlspecialchars($machine['unit_name'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Модель</th>
                            <td><?= htmlspecialchars($machine['name']) ?></td>
                        </tr>
                        <tr>
                            <th>Регистрационный номер</th>
                            <td><?= htmlspecialchars($machine['registr_plate']) ?></td>
                        </tr>
                        <tr>
                            <th>Вид топлива</th>
                            <td><?= htmlspecialchars($machine['fuel_name'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Порядок сортировки</th>
                            <td><?= $machine['order'] ?></td>
                        </tr>
                        <tr>
                            <th>Линейная норма</th>
                            <td><?=isset($machine['linear_norm']) ? number_format($machine['linear_norm'], 2, '.', '') : '—' ?></td>
                        </tr>
                        <tr>
                            <th>Дата выпуска</th>
                            <td><?= isset($machine['release_date']) ? date('d.m.Y', strtotime($machine['release_date'])) : '—' ?></td>
                        </tr>
                        <tr>
                            <th>Дата редактирования</th>
                            <td><?= $machine['data_edit'] ?></td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>
                                <?php if ($machine['is_active']): ?>
                                    <span class="badge bg-success">Активен</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Неактивен</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>