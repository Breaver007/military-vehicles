<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Просмотр складов</h3>
                    <div>
                        <a href="/military-ticket-from-local-stock/edit/<?= $localStocks['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                        <a href="/military-ticket-from-local-stock" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td><?= $localStocks['id'] ?></td>
                        </tr>
                        <tr>
                            <th>Дата заполнения</th>
                            <td><?= htmlspecialchars($localStocks['date']) ?></td>
                        </tr>
                        <tr>
                            <th>Откуда</th>
                            <td><?= htmlspecialchars($localStocks['name']) ?></td>
                        </tr>
                        <tr>
                            <th>Наименование документа</th>
                            <td><?= htmlspecialchars($localStocks['name_document']) ?></td>
                        </tr>
                        <tr>
                            <th>№, дата документа</th>
                            <td><?= htmlspecialchars($localStocks['number_document']) ?></td>
                        </tr>
                        <tr>
                            <th>Дата редактирования</th>
                            <td><?= $localStocks['date_edit'] ?></td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>
                                <?php if ($localStocks['is_active']): ?>
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