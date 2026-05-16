<!-- Модальное окно для добавления заправки (места) -->
<div class="modal fade" id="fuelPlacesModal" tabindex="-1" aria-labelledby="fuelPlacesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fuelPlacesModalLabel">
                    <i class="bi bi-geo-alt"></i> Добавить заправку (Прочие заправки)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="fuel_places_date" class="form-label">Дата заправки *</label>
                    <input type="date"
                           class="form-control"
                           id="fuel_places_date"
                           value="<?= date('Y-m-d') ?>"
                           required>
                </div>
                <div class="mb-3">
                    <label for="fuel_places_type" class="form-label">Место заправки *</label>
                    <select class="form-select" id="fuel_places_type" required>
                        <option value="">Выберите место</option>
                        <?php if (!empty($MilitaryFuelOtherPlaces)): ?>
                            <?php foreach ($MilitaryFuelOtherPlaces as $place): ?>
                                <option value="<?= $place['id'] ?>">
                                    <?= htmlspecialchars($place['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fuel_places_value" class="form-label">Количество (литров) *</label>
                    <input type="number"
                           step="0.01"
                           class="form-control"
                           id="fuel_places_value"
                           placeholder="0.00"
                           required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Отмена
                </button>
                <button type="button" class="btn btn-primary" onclick="addFuelPlacesRecord()">
                    <i class="bi bi-plus-lg"></i> Добавить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Модальное окно подтверждения удаления для places -->
<div class="modal fade" id="deleteConfirmModalPlaces" tabindex="-1">
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
                <button type="button" class="btn btn-danger" id="confirmDeletePlacesBtn">Удалить</button>
            </div>
        </div>
    </div>
</div>