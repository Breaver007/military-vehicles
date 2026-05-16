<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-white py-3">
            <h3 class="h4 mb-0">Создать новую запись в эксплуатационную карточку</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['errors'] as $field => $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form action="/military-ticket/store" method="POST" class="needs-validation">
                <!-- Скрытые поля -->
                <div class="d-none">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="number" readonly required class="form-control" id="m_model_machine" name="m_model_machine" value="<?= $data['idMachines'] ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="number" readonly required class="form-control" id="year" name="year" value="<?= $data['year'] ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="number" readonly required class="form-control" id="month" name="month" value="<?= $data['month'] ?>">
                        </div>
                        <div class="col-md-3">
                            <div id="military-norms-data" data-norms='<?= json_encode($data['MilitaryNorm'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'></div>
                        </div>
                    </div>
                </div>

                <!-- Путевой лист -->
                <div class="card mb-4 border-light">
                    <div class="card-header bg-light bg-opacity-25 py-2">
                        <span class="fw-semibold">📋 Путевой лист</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-6">
                                <label class="form-label small text-secondary">Дата</label>
                                <input type="date" class="form-control" id="data_ticket" name="data_ticket" value="<?= date("{$data['year']}-{$data['month']}-d") ?>">
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <label class="form-label small text-secondary">Номер путевого листа</label>
                                <input type="number" class="form-control" id="number_ticket" name="number_ticket" placeholder="Введите номер">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Предварительные данные -->
                <div class="card mb-4 border-light">
                    <div class="card-header bg-light bg-opacity-25 py-2">
                        <span class="fw-semibold">📊 Предварительные данные</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small text-secondary">
                                    Спидометр на начало дня
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Участвует в расчете общего пробега">
                                        <i class="bi bi-calculator-fill"></i> расчет
                                    </span>
                                </label>
                                <input type="number" required oninput="updateTicketKilometresWork()" class="form-control" id="kilometres_speedometer_start" name="kilometres_speedometer_start" placeholder="0" min="<?= $data['maxKilometres'] ?: 0 ?>" value="<?= $data['maxKilometres'] ?: '' ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-secondary">
                                    Спидометр на конец дня
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Участвует в расчете общего пробега">
                                        <i class="bi bi-calculator-fill"></i> расчет
                                    </span>
                                </label>
                                <input type="number" required oninput="updateTicketKilometresWork()" class="form-control" id="kilometres_speedometer_end" name="kilometres_speedometer_end" placeholder="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-secondary">Кол-во дней</label>
                                <input type="number" required class="form-control" id="day_count" name="day_count" placeholder="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-secondary">
                                    Норма расхода
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Выбор нормы влияет на все расчеты">
                                        <i class="bi bi-calculator-fill"></i> расчет
                                    </span>
                                </label>
                                <select class="form-select" onchange="loadNorms()" required id="m_norm" name="m_norm">
                                    <?php if ($data['MilitaryNorm']): ?>
                                        <?php foreach ($data['MilitaryNorm'] as $dataNorm): ?>
                                            <option value="<?= $dataNorm['id'] ?>"><?= $dataNorm['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Данные из путевки -->
                        <div class="bg-light bg-opacity-10 p-3 rounded-3 mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-semibold small">✍️ Данные из путевки</span>
                                <span class="badge bg-warning bg-opacity-10 text-warning">поля участвуют в расчете</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-6 col-md-2">
                                    <label class="form-label small text-secondary">Город (км)</label>
                                    <input type="number" oninput="updateTicketKilometres()" min="0" class="form-control calc-field" id="kilometres_city" name="kilometres_city" placeholder="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small text-secondary">Трасса (км)</label>
                                    <input type="number" oninput="updateTicketKilometres()" min="0" class="form-control calc-field" id="kilometres_trail" name="kilometres_trail" placeholder="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small text-secondary">Грунт (км)</label>
                                    <input type="number" oninput="updateTicketKilometres()" min="0" class="form-control calc-field" id="kilometres_ground" name="kilometres_ground" placeholder="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small text-secondary">Линейная (км)</label>
                                    <input type="number" oninput="updateTicketKilometres()" min="0" class="form-control calc-field" id="kilometres_linear" name="kilometres_linear" placeholder="0">
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small text-secondary">Км по путевке</label>
                                    <input type="number" class="form-control bg-light" id="kilometres_ticket" name="kilometres_ticket" readonly>
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small text-secondary">
                                        К списанию
                                        <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Влияет на расход топлива">
                                            <i class="bi bi-calculator-fill"></i>
                                        </span>
                                    </label>
                                    <input type="number" oninput="updateSpent()" min="0" class="form-control calc-field" id="ticket_write_off" name="ticket_write_off" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <!-- Расчет по нормам -->
                        <div class="bg-light bg-opacity-10 p-3 rounded-3 mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-semibold small">⚡ Расчет по нормам</span>
                                <span class="badge bg-success bg-opacity-10 text-success">автоматический расчет</span>
                            </div>
                            <div class="row g-3">
                                <?php
                                $normFields = [
                                    'city' => 'Город',
                                    'trail' => 'Трасса',
                                    'ground' => 'Грунт',
                                    'linear' => 'Линейная',
                                    'cargo' => 'Груз',
                                    'pump' => 'Насос'
                                ];
                                foreach ($normFields as $key => $label):
                                    ?>
                                    <div class="col-6 col-md-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white" id="normal_<?= $key ?>">0</span>
                                            <input type="number" readonly class="form-control bg-light" id="calc_normal_<?= $key ?>" name="calc_normal_<?= $key ?>" placeholder="0">
                                        </div>
                                        <label class="small text-secondary mt-1"><?= $label ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Данные по грузу/весу -->
                        <div class="bg-light bg-opacity-10 p-3 rounded-3 mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-semibold small">🚚 Данные по грузу/весу</span>
                                <span class="badge bg-warning bg-opacity-10 text-warning">поля участвуют в расчете</span>
                            </div>
                            <div class="row g-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label small text-secondary">Груз <?= $i ?></label>
                                        <input type="number" oninput="updateCargo()" min="0" class="form-control calc-field" id="cargo_<?= $i ?>" name="cargo_<?= $i ?>" placeholder="0">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label small text-secondary">Вес <?= $i ?> (т)</label>
                                        <input type="number" oninput="updateWeight()" min="0" step="0.001" class="form-control calc-field" id="weight_<?= $i ?>" name="weight_<?= $i ?>" placeholder="0">
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <!-- Выполненные работы -->
                        <div class="bg-light bg-opacity-10 p-3 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-semibold small">🔧 Выполненные работы</span>
                                <span class="badge bg-warning bg-opacity-10 text-warning">участвует в расчете</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-secondary">Моточасы работа насоса</label>
                                    <input type="number" oninput="updateCalcNormalsPump()" min="0" class="form-control calc-field" id="pump" name="pump" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Пройдено километров -->
                <div class="card mb-4 border-light">
                    <div class="card-header bg-light bg-opacity-25 py-2">
                        <span class="fw-semibold">📏 Пройдено километров (отработано моточасов)</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-secondary">
                                    С грузом
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Участвует в расчете тонна-километров">
                                        <i class="bi bi-calculator-fill"></i> расчет
                                    </span>
                                </label>
                                <input type="number" oninput="updateCargoNo()" min="0" class="form-control calc-field" id="cargo" name="cargo" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-secondary">Без груза</label>
                                <input type="number" disabled class="form-control bg-light" id="cargo-no" name="cargo-no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-secondary">
                                    Всего
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Автоматически рассчитывается из показаний спидометра">
                                        <i class="bi bi-calculator-fill"></i> авто
                                    </span>
                                </label>
                                <input type="number" oninput="updateCargoNo()" min="0" class="form-control calc-field" id="kilometres_speedometer" name="kilometres_speedometer" placeholder="0">
                            </div>
                        </div>
                        <div id="alert_check_kilometres_warning_Match"></div>
                    </div>
                </div>

                <!-- Выполненная работа -->
                <div class="card mb-4 border-light">
                    <div class="card-header bg-light bg-opacity-25 py-2">
                        <span class="fw-semibold">📈 Выполненная работа</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-secondary">
                                    Тонн
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Сумма весов грузов">
                                        <i class="bi bi-calculator-fill"></i> авто
                                    </span>
                                </label>
                                <input type="text" oninput="updateCompletedWork()" class="form-control calc-field" id="completed_work" name="completed_work" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-secondary">Тонна-километров</label>
                                <input type="number" disabled class="form-control bg-light" id="completed_work_km" name="completed_work_km" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Материальные средства -->
                <div class="card mb-4 border-light">
                    <div class="card-header bg-light bg-opacity-25 py-2">
                        <span class="fw-semibold">⛽ Материальные средства, л</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Остаток на начало (горючее)</label>
                                <input type="text" value="<?= $data['maxOpeningBalanceFuel'] ?>" class="form-control" id="opening_balance_fuel" name="opening_balance_fuel">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Остаток на начало (масло)</label>
                                <input type="text" class="form-control" id="opening_balance_butter" name="opening_balance_butter">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Получено горючего</label>
                                <input type="text" class="form-control" id="taken_fuel" name="taken_fuel">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Получено масла</label>
                                <input type="text" class="form-control" id="taken_butter" name="taken_butter">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">
                                    Израсходовано горючего
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip" title="Должно совпадать с положенным по норме">
                                        <i class="bi bi-calculator-fill"></i>
                                    </span>
                                </label>
                                <input type="text" class="form-control calc-field" id="spent_fuel" name="spent_fuel">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Израсходовано масла</label>
                                <input type="text" class="form-control" id="spent_butter" name="spent_butter">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">
                                    Положено по норме горючего
                                    <span class="badge bg-success bg-opacity-10 text-success ms-1" data-bs-toggle="tooltip" title="Автоматический расчет">
                                        <i class="bi bi-calculator-fill"></i> авто
                                    </span>
                                </label>
                                <input type="text" class="form-control bg-light" id="normal_fuel" name="normal_fuel" readonly>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Положено по норме масла</label>
                                <input type="text" class="form-control" id="normal_butter" name="normal_butter">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Остаток на конец (горючее)</label>
                                <input type="text" class="form-control" id="closing_balance_fuel" name="closing_balance_fuel">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Остаток на конец (масло)</label>
                                <input type="text" class="form-control" id="closing_balance_butter" name="closing_balance_butter">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Экономия горючего</label>
                                <input type="text" class="form-control" id="saving_fuel" name="saving_fuel">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Экономия масла</label>
                                <input type="text" class="form-control" id="saving_butter" name="saving_butter">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Перерасход горючего</label>
                                <input type="text" class="form-control" id="excessive_fuel" name="excessive_fuel">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label small text-secondary">Перерасход масла</label>
                                <input type="text" class="form-control" id="excessive_butter" name="excessive_butter">
                            </div>
                        </div>
                        <div id="alert_check_ticket_writes_Match"></div>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div class="d-flex justify-content-between gap-2 pt-3 border-top">
                    <a href="/military-ticket/<?= $data['idMachines'] ?? $data['machine_id'] ?>/<?= $data['month'] ?>/<?= $data['year'] ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Назад
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Создать
                    </button>
                </div>
            </form>
            <?php unset($_SESSION['old']); ?>
        </div>
    </div>
</div>

<!-- Подключение стилей для индикации полей с расчетами -->
<style>
    .calc-field {
        border-left: 3px solid #0dcaf0 !important;
    }
    .calc-field:focus {
        border-left-color: #0a58ca !important;
        box-shadow: none;
    }
    [data-bs-toggle="tooltip"] {
        cursor: help;
    }
    .bg-light.bg-opacity-10 {
        background-color: rgba(var(--bs-light-rgb), 0.05) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        loadNorms(<?= json_encode($data['MilitaryNorm']) ?>);
        document.getElementById('m_norm').addEventListener('change', function() {
            loadNorms(<?= json_encode($data['MilitaryNorm']) ?>);
        });
    });
</script>