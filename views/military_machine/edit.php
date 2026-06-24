<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Редактировать технику</h3>
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

                    <form action="/military-machine/update/<?= $machine['id'] ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="m_unit" class="form-label">Воинская часть *</label>
                                <select class="form-select" id="m_unit" name="m_unit" required>
                                    <option value="">Выберите воинскую часть</option>
                                    <?php foreach ($units as $unit): ?>
                                        <?php
                                        // Определяем, нужно ли выбрать эту опцию
                                        $selected = '';
                                        if (isset($_SESSION['old']['m_unit']) && $_SESSION['old']['m_unit'] == $unit['id']) {
                                            $selected = 'selected';
                                        } elseif (!isset($_SESSION['old']) && isset($machine['m_unit']) && $machine['m_unit'] == $unit['id']) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?= $unit['id'] ?>" <?= $selected ?>>
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
                                        <?php
                                        // Определяем, нужно ли выбрать эту опцию
                                        $selected = '';
                                        if (isset($_SESSION['old']['m_fuel']) && $_SESSION['old']['m_fuel'] == $fuel['id']) {
                                            $selected = 'selected';
                                        } elseif (!isset($_SESSION['old']) && isset($machine['m_fuel']) && $machine['m_fuel'] == $fuel['id']) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?= $fuel['id'] ?>" <?= $selected ?>>
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
                                       value="<?= htmlspecialchars($_SESSION['old']['name'] ?? $machine['name']) ?>"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="registr_plate" class="form-label">Регистрационный номер *</label>
                                <input type="text"
                                       class="form-control"
                                       id="registr_plate"
                                       name="registr_plate"
                                       value="<?= htmlspecialchars($_SESSION['old']['registr_plate'] ?? $machine['registr_plate']) ?>"
                                       required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="order" class="form-label">Порядок сортировки *</label>
                                <input type="number"
                                       class="form-control"
                                       id="order"
                                       name="order"
                                       value="<?= htmlspecialchars($_SESSION['old']['order'] ?? $machine['order']) ?>"
                                       step="1"
                                       required>
                                <small class="text-muted">Рекомендуется использовать шаг 10</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="linear_norm" class="form-label">Линейная норма</label>
                                <input type="number"
                                       class="form-control"
                                       id="linear_norm"
                                       name="linear_norm"
                                       value="<?= htmlspecialchars($_SESSION['old']['linear_norm'] ?? $machine['linear_norm'] ?? '') ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="release_date" class="form-label">Дата выпуска</label>
                                <input type="date"
                                       class="form-control"
                                       id="release_date"
                                       name="release_date"
                                       value="<?= htmlspecialchars($_SESSION['old']['release_date'] ?? $machine['release_date'] ?? '') ?>">
                            </div>

                            <div class="col-md-6 justify-content-center align-content-center mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           role="switch"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                        <?php
                                        $checked = $_SESSION['old']['is_active'] ?? $machine['is_active'] ?? false;
                                        echo $checked ? 'checked' : '';
                                        ?>>
                                    <label class="form-check-label" for="is_active">Активно</label>
                                </div>
                            </div>
                        </div>



                        <div class="d-flex justify-content-between">
                            <a href="/military-machine" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Обновить
                            </button>
                        </div>
                    </form>
                    <?php unset($_SESSION['old']); ?>
                </div>
            </div>
        </div>
    </div>
</div>