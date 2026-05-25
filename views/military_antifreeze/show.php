<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Просмотр вида антифриза</h3>
                    <div>
                        <a href="/military-antifreeze/edit/<?= $antifreeze['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Редактировать</a>
                        <a href="/military-antifreeze" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Назад</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th style="width:200px;">ID</th><td><?= $antifreeze['id'] ?></td></tr>
                        <tr><th>Название</th><td><?= htmlspecialchars($antifreeze['name']) ?></td></tr>
                        <tr><th>Дата редактирования</th><td><?= $antifreeze['date_edit'] ?></td></tr>
                        <tr><th>Статус</th><td><?= $antifreeze['is_active'] ? '<span class="badge bg-success">Активен</span>' : '<span class="badge bg-danger">Неактивен</span>' ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
