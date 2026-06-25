<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h5 class="mb-0">Обновление проекта</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Нажмите кнопку, чтобы выполнить <code>git pull</code> и <code>composer install</code>.</p>

                    <form action="/project-update/run" method="POST">
                        <button type="submit" class="btn btn-primary" onclick="this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm\'></span> Выполняется...'; this.form.submit();">
                            <i class="bi bi-arrow-repeat"></i> Обновить проект
                        </button>
                    </form>

                    <?php if (isset($_SESSION['update_output'])): ?>
                        <hr>
                        <h6>Результат:</h6>
                        <pre class="bg-dark text-light p-3 rounded" style="max-height: 500px; overflow-y: auto; font-size: 0.85rem;"><?= htmlspecialchars($_SESSION['update_output']) ?></pre>
                        <?php unset($_SESSION['update_output']); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="/" class="btn btn-outline-secondary btn-sm">На главную</a>
            </div>
        </div>
    </div>
</div>
