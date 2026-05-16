<div class="container-fluid py-5 my-4">
    <div class="row ">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Добавить технику</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $field => $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form action="/military-machine/store" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="m_unit" class="form-label">Воинская часть *</label>
                                <select class="form-select" id="m_unit" name="m_unit" required>
                                    <option value="">Выберите воинскую часть</option>
                                    <?php foreach ($units as $unit): ?>
                                        <option value="<?= $unit['id'] ?>"
                                            <?= (isset($_SESSION['old']['m_unit']) && $_SESSION['old']['m_unit'] == $unit['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($unit['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="m_fuel" class="form-label">Вид топлива *</label>
                                <select class="form-select" id="m_fuel" name="m_fuel" required>
                                    <option value="">Выберите вид топлива</option>
                                    <?php foreach ($fuels as $fuel): ?>
                                        <option value="<?= $fuel['id'] ?>"
                                            <?= (isset($_SESSION['old']['m_fuel']) && $_SESSION['old']['m_fuel'] == $fuel['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fuel['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Модель *</label>
                                <input type="text"
                                       class="form-control"
                                       id="name"
                                       name="name"
                                       value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="registr_plate" class="form-label">Регистрационный номер *</label>
                                <input type="text"
                                       class="form-control"
                                       id="registr_plate"
                                       name="registr_plate"
                                       value="<?= htmlspecialchars($_SESSION['old']['registr_plate'] ?? '') ?>"
                                       required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="order" class="form-label">Порядок сортировки *</label>
                                <input type="number"
                                       class="form-control"
                                       id="order"
                                       name="order"
                                       value="<?= htmlspecialchars($_SESSION['old']['order'] ?? $nextOrder) ?>"
                                       step="10"
                                       required>
                                <small class="text-muted">Рекомендуется использовать шаг 10</small>
                            </div>
                        </div>


                        <div class="d-flex justify-content-between">
                            <a href="/military-machine" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Сохранить
                            </button>
                        </div>
                    </form>
                    <?php unset($_SESSION['old']); ?>
                </div>
            </div>
        </div>
    </div>
</div>