<!-- Модальное окно для добавления масла -->
<div class="modal fade" id="butterModal" tabindex="-1" aria-labelledby="butterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="butterModalLabel">
                    <i class="bi bi-droplet"></i> Добавить масло
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="butter_date" class="form-label">Дата *</label>
                    <input type="date"
                           class="form-control"
                           id="butter_date"
                           value="<?= date('Y-m-d') ?>"
                           required>
                </div>
                <div class="mb-3">
                    <label for="butter_type" class="form-label">Вид масла *</label>
                    <select class="form-select" id="butter_type" required>
                        <option value="">Выберите вид масла</option>
                        <?php if (!empty($MilitaryButter)): ?>
                            <?php foreach ($MilitaryButter as $butter): ?>
                                <option value="<?= $butter['id'] ?>">
                                    <?= htmlspecialchars($butter['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="butter_value" class="form-label">Количество (литров) *</label>
                    <input type="number"
                           step="0.01"
                           class="form-control"
                           id="butter_value"
                           placeholder="0.00"
                           required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Отмена
                </button>
                <button type="button" class="btn btn-primary" onclick="addButterRecord()">
                    <i class="bi bi-plus-lg"></i> Добавить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteConfirmModalButter" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Удалить эту запись?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButterBtn">Удалить</button>
            </div>
        </div>
    </div>
</div>
