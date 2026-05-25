<div class="container-fluid pt-5 mt-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Добавить вид антифриза</h3>
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
                    <form action="/military-antifreeze/store" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Название антифриза *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="/military-antifreeze" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Назад</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Сохранить</button>
                        </div>
                    </form>
                    <?php unset($_SESSION['old']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
