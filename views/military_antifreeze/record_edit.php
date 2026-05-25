<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Редактировать запись антифриза</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>
                    <form action="/military-antifreeze/records/update/<?= $record['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label for="date" class="form-label">Дата *</label>
                            <input type="date" class="form-control" id="date" name="date"
                                   value="<?= $_SESSION['old']['date'] ?? $record['date'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="mt_antifreeze_id" class="form-label">Вид антифриза *</label>
                            <select class="form-select" id="mt_antifreeze_id" name="mt_antifreeze_id" required>
                                <option value="">Выберите антифриз</option>
                                <?php foreach ($MilitaryAntifreeze as $a): ?>
                                    <option value="<?= $a['id'] ?>" <?= (($_SESSION['old']['mt_antifreeze_id'] ?? $record['mt_antifreeze_id']) == $a['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($a['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="machine_id" class="form-label">Техника *</label>
                            <select class="form-select" id="machine_id" name="machine_id" required>
                                <option value="">Выберите технику</option>
                                <?php foreach ($MilitaryModelMachine as $m): ?>
                                    <option value="<?= $m['id'] ?>" <?= (($_SESSION['old']['machine_id'] ?? $record['machine_id']) == $m['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['name'] . ' ' . ($m['registr_plate'] ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">Количество *</label>
                            <input type="number" class="form-control" id="value" name="value" min="1"
                                   value="<?= $_SESSION['old']['value'] ?? $record['value'] ?>" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="/military-antifreeze/records" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Назад</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Обновить</button>
                        </div>
                    </form>
                    <?php unset($_SESSION['old']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
