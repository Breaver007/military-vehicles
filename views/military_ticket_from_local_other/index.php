<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Виды других заправок</h3>
                    <a href="/military-ticket-from-local-other/create" class="btn btn-primary">
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
                                <th>Дата заполнения</th>
                                <th>Откуда</th>
                                <th>Наименование документа</th>
                                <th>№, дата документа</th>
                                <th>Дата редактирования</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($localOthers as $localOther): ?>
                                <tr>
                                    <td><?= $localOther['id'] ?></td>
                                    <td><?= $localOther['date'] ?></td>
                                    <td><?= htmlspecialchars($localOther['name']) ?></td>
                                    <td><?= htmlspecialchars($localOther['name_document']) ?></td>
                                    <td><?= htmlspecialchars($localOther['number_document']) ?></td>
                                    <td><?= $localOther['date_edit'] ?></td>
                                    <td>
                                        <?php if ($localOther['is_active']): ?>
                                            <span class="badge bg-success">Активен</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Неактивен</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/military-ticket-from-local-other/<?= $localStock['id'] ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Просмотр">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/military-ticket-from-local-other/edit/<?= $localStock['id'] ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Редактировать">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/military-ticket-from-local-other/delete/<?= $localStock['id'] ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Вы уверены, что хотите удалить этот вид топлива?')"
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