<!-- Модальное окно для добавления заправки (другие источники) -->
<div class="modal fade" id="fuelOtherModal" tabindex="-1" aria-labelledby="fuelOtherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fuelOtherModalLabel">
                    <i class="bi bi-truck"></i> Добавить заправку (Заправки других в/ч)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="fuel_other_date" class="form-label">Дата заправки *</label>
                    <input type="date"
                           class="form-control"
                           id="fuel_other_date"
                           value="<?= date('Y-m-d') ?>"
                           required>
                </div>
                <div class="mb-3">
                    <label for="fuel_other_type" class="form-label">Источник заправки *</label>
                    <select class="form-select" id="fuel_other_type" required>
                        <option value="">Выберите источник</option>
                        <?php if (!empty($MilitaryOtherStock)): ?>
                            <?php foreach ($MilitaryOtherStock as $otherStock): ?>
                                <option value="<?= $otherStock['id'] ?>">
                                    <?= htmlspecialchars($otherStock['name'] . " " . $otherStock['name_document'] . " " . $otherStock['number_document']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fuel_other_value" class="form-label">Количество (литров) *</label>
                    <input type="number"
                           step="0.01"
                           class="form-control"
                           id="fuel_other_value"
                           placeholder="0.00"
                           required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Отмена
                </button>
                <button type="button" class="btn btn-primary" onclick="addFuelOtherRecord()">
                    <i class="bi bi-plus-lg"></i> Добавить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Модальное окно подтверждения удаления для other -->
<div class="modal fade" id="deleteConfirmModalOther" tabindex="-1">
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
                <button type="button" class="btn btn-danger" id="confirmDeleteOtherBtn">Удалить</button>
            </div>
        </div>
    </div>
</div>