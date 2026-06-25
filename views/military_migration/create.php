<div class="container-fluid py-5 my-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Новая миграция</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/military-migration/store" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Название миграции</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>"
                                   placeholder="Например: add_linear_norm_to_machines"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="sql" class="form-label">SQL-запрос</label>
                            <textarea class="form-control font-monospace"
                                      id="sql"
                                      name="sql"
                                      rows="10"
                                      style="font-size: 0.85rem;"
                                      placeholder="ALTER TABLE `military_model_machine` ADD COLUMN ..."
                                      required><?= htmlspecialchars($_SESSION['old']['sql'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/military-migration" class="btn btn-secondary">
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
