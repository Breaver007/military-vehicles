<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Эксплуатационная карточка №<?= htmlspecialchars($ticket['ticket_number']) ?></h3>
                    <div>
                        <a href="/military-ticket/edit/<?= $ticket['id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                        <a href="/military-ticket" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td><?= $ticket['id'] ?></td>
                        </tr>
                        <tr>
                            <th>Номер карточки</th>
                            <td><?= htmlspecialchars($ticket['ticket_number']) ?></td>
                        </tr>
                        <tr>
                            <th>Техника</th>
                            <td>
                                <?php if ($machine): ?>
                                    <strong><?= htmlspecialchars($machine['name']) ?></strong><br>
                                    Рег. номер: <?= htmlspecialchars($machine['registr_plate']) ?>
                                <?php else: ?>
                                    <span class="text-muted">Не указана</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Период</th>
                            <td>
                                <?= date('d.m.Y', strtotime($ticket['start_date'])) ?> -
                                <?= date('d.m.Y', strtotime($ticket['end_date'])) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Дата создания</th>
                            <td><?= date('d.m.Y H:i:s', strtotime($ticket['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Дата обновления</th>
                            <td><?= date('d.m.Y H:i:s', strtotime($ticket['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>