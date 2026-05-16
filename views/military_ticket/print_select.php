<div class="container pt-5 mt-4">
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-printer"></i> Печать эксплуатационной карточки</h3>
            </div>
            <div class="card-body">
                <div class="badge rounded-pill text-bg-primary fs-6 mb-2">
                    <i class="bi bi-truck"></i>
                    <strong>Техника:</strong> <?= htmlspecialchars($machineName) ?>
                </div>

                <form action="/military-ticket/print" method="POST" id="printForm" target="_blank">
                    <input type="hidden" name="idModelMachine" value="<?= $idModelMachine ?>">

                    <div class="mb-2">
                        <label for="start_date" class="form-label">
                            <i class="bi bi-calendar-start"></i> Дата начала
                        </label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               class="form-control date-input"
                               value="<?= date('Y-m-01') ?>"
                               required>
                    </div>

                    <div class="mb-2">
                        <label for="end_date" class="form-label">
                            <i class="bi bi-calendar-end"></i> Дата окончания
                        </label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               class="form-control date-input"
                               value="<?= date('Y-m-d') ?>"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-calendar-week"></i> Быстрый выбор
                        </label>
                        <div class="period-buttons">
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="month">
                                <i class="bi bi-calendar-month"></i> Текущий месяц
                            </button>
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="quarter">
                                <i class="bi bi-calendar3"></i> Текущий квартал
                            </button>
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="year">
                                <i class="bi bi-calendar-year"></i> Текущий год
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="/military-ticket/<?= $idModelMachine ?>/<?= date('n') ?>/<?= date('Y') ?>"
                           class="btn btn-secondary flex-grow-1">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-printer"></i> Печать
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Функция для установки периода
    function setPeriod(startDate, endDate) {
        document.getElementById('start_date').value = startDate;
        document.getElementById('end_date').value = endDate;
    }

    // Получить первый и последний день месяца
    function getMonthBounds(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        return {
            start: firstDay.toISOString().split('T')[0],
            end: lastDay.toISOString().split('T')[0]
        };
    }

    // Получить первый и последний день квартала
    function getQuarterBounds(date) {
        const year = date.getFullYear();
        const quarter = Math.floor(date.getMonth() / 3);
        const firstMonth = quarter * 3;
        const lastMonth = firstMonth + 2;
        const firstDay = new Date(year, firstMonth, 1);
        const lastDay = new Date(year, lastMonth + 1, 0);
        return {
            start: firstDay.toISOString().split('T')[0],
            end: lastDay.toISOString().split('T')[0]
        };
    }

    // Получить первый и последний день года
    function getYearBounds(date) {
        const year = date.getFullYear();
        const firstDay = new Date(year, 0, 1);
        const lastDay = new Date(year, 11, 31);
        return {
            start: firstDay.toISOString().split('T')[0],
            end: lastDay.toISOString().split('T')[0]
        };
    }

    // Обработчики кнопок
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const now = new Date();
            let bounds;

            switch (this.dataset.period) {
                case 'month':
                    bounds = getMonthBounds(now);
                    break;
                case 'quarter':
                    bounds = getQuarterBounds(now);
                    break;
                case 'year':
                    bounds = getYearBounds(now);
                    break;
            }

            if (bounds) {
                setPeriod(bounds.start, bounds.end);
            }
        });
    });

    // Валидация дат
    document.getElementById('printForm').addEventListener('submit', function (e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate > endDate) {
            e.preventDefault();
            alert('Дата начала не может быть позже даты окончания!');
            return false;
        }

        return true;
    });
</script>
</body>
</html>