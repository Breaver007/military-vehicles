<!-- Модальное окно для добавления заправки -->
<div class="modal fade" id="fuelModal" tabindex="-1" aria-labelledby="fuelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fuelModalLabel">
                    <i class="bi bi-fuel-pump"></i> Добавить заправку
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="fuel_date" class="form-label">Дата заправки *</label>
                    <input type="date"
                           class="form-control"
                           id="fuel_date"
                           value="<?= date('Y-m-d') ?>"
                           required>
                </div>
                <div class="mb-3">
                    <label for="fuel_type" class="form-label">Источник заправки *</label>
                    <select class="form-select" id="fuel_type" required>
                        <option value="">Выберите источник</option>
                        <?php if (!empty($MilitaryLocalStock)): ?>
                            <?php foreach ($MilitaryLocalStock as $stock): ?>
                                <option value="<?= $stock['id'] ?>">
                                    <?= htmlspecialchars($stock['name'] . " " . $stock['name_document'] . " " . $stock['number_document']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fuel_value" class="form-label">Количество (литров) *</label>
                    <input type="number"
                           step="0.01"
                           class="form-control"
                           id="fuel_value"
                           placeholder="0.00"
                           required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Отмена
                </button>
                <button type="button" class="btn btn-primary" onclick="addFuelRecord()">
                    <i class="bi bi-plus-lg"></i> Добавить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Удалить эту заправку?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Удалить</button>
            </div>
        </div>
    </div>
</div>
