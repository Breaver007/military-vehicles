<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Редактировать вид топлива</h3>
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

                    <form action="/military-fuel/update/<?= $fuel['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Название топлива *</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   value="<?= htmlspecialchars($_SESSION['old']['name'] ?? $fuel['name']) ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                    <?php
                                    $checked = $_SESSION['old']['is_active'] ?? $fuel['is_active'] ?? false;
                                    echo $checked ? 'checked' : '';
                                    ?>>
                                <label class="form-check-label" for="is_active">Активно</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/military-fuel" class="btn btn-secondary">
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