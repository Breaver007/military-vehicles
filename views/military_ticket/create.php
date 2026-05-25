<div class="container-fluid py-5 my-4">
<!--        --><?php
//            echo "<pre>";
//            var_dump($data);
//            echo "</pre>";
//        ?>
    <div class="card">
        <div class="card-header">
            <h3>Создать новую запись в путевой лист</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['errors'] as $field => $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form action="/military-ticket/store" method="POST" id="mainForm">
                <div class="row">
                    <div class="col-12 d-none">
                        <div class="row gx-2">
                            <input type="hidden" name="temp_id" id="temp_id" value="<?= $temp_id ?? '' ?>">
                            <div class="form-floating col-3 my-2">
                                <input type="number"
                                       readonly
                                       required
                                       class="form-control"
                                       id="m_model_machine"
                                       name="m_model_machine"
                                       value="<?= $data['idMachines'] ?>"
                                       placeholder="Машина">
                                <label for="m_model_machine">
                                    Машина
                                </label>
                            </div>
                            <div class="form-floating col-3 my-2">
                                <input type="number"
                                       readonly
                                       required
                                       class="form-control"
                                       id="year"
                                       name="year"
                                       value="<?= $data['year'] ?>"
                                       placeholder="Год">
                                <label for="year">
                                    Год
                                </label>
                            </div>
                            <div class="form-floating col-3 my-2">
                                <input type="number"
                                       readonly
                                       required
                                       class="form-control"
                                       id="month"
                                       name="month"
                                       value="<?= $data['month'] ?>"
                                       placeholder="month">
                                <label for="month">
                                    месяц
                                </label>
                            </div>
                            <div class="form-floating col-3 my-2">
                                <div id="military-norms-data"
                                     data-norms='<?= json_encode($data['MilitaryNorm'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <legend>Путевой лист</legend>
                            <div class="row gx-2">
                                <div class="form-floating col-6">
                                    <input type="date"
                                           class="form-control"
                                           id="data_ticket"
                                           name="data_ticket"
                                           value="<?= date("{$data['year']}-{$data['month']}-d") ?>"
                                           placeholder="Дата">
                                    <label for="data_ticket">Дата</label>
                                </div>
                                <div class="form-floating col-6">
                                    <input type="number"
                                           class="form-control"
                                           id="number_ticket"
                                           name="number_ticket"
                                           placeholder="номер">
                                    <label for="number_ticket">номер</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <legend>Предварительные данные</legend>
                            <div class="row gx-2">
                                <div class="form-floating col-4">
                                    <input type="number"
                                           required
                                           oninput="updateTicketKilometresWork()"
                                           class="form-control"
                                           id="kilometres_speedometer_start"
                                           name="kilometres_speedometer_start"
                                           placeholder="Спидометр на начало дня"
                                           min="<?= $data['maxKilometres'] ?: 0 ?>"
                                           value="<?= $data['maxKilometres'] ?: "" ?>">
                                    <label for="kilometres_speedometer_start">
                                        Спидометр на начало дня
                                        <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip"
                                              title="Участвует в расчете общего пробега">
                                        <i class="bi bi-calculator-fill"></i> расчет
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-4">
                                    <input type="number"
                                           required
                                           oninput="updateTicketKilometresWork()"
                                           class="form-control"
                                           id="kilometres_speedometer_end"
                                           name="kilometres_speedometer_end"
                                           placeholder="Спидометр на конец дня"
                                           value="">
                                    <label for="kilometres_speedometer_end">
                                        Спидометр на конец дня
                                        <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip"
                                              title="Участвует в расчете общего пробега">
                                        <i class="bi bi-calculator-fill"></i> расчет
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-2">
                                    <input type="number"
                                           required
                                           class="form-control"
                                           id="day_count"
                                           name="day_count"
                                           placeholder="Спидометр на начало дня">
                                    <label for="day_count">
                                        Кол-во дней
                                    </label>
                                </div>
                                <div class="form-floating col-2">
                                    <select class="form-select"
                                            onchange="loadNorms()"
                                            required
                                            id="m_norm"
                                            name="m_norm"
                                            placeholder="Выбор нормы">
                                        <?php
                                        if ($data['MilitaryNorm']) {
                                            foreach ($data['MilitaryNorm'] as $key => $dataNorm) {
                                                ?>
                                                <option value="<?= $dataNorm['id'] ?>">
                                                    <?= $dataNorm['name'] ?>
                                                </option>
                                                <?php
                                            }
                                        } ?>
                                    </select>
                                    <label for="m_norm">
                                        Выбор нормы
                                        <span class="badge bg-info bg-opacity-10 text-info ms-1" data-bs-toggle="tooltip"
                                              title="Выбор нормы влияет на все расчеты">
                                        <i class="bi bi-calculator-fill"></i> расчет
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="row gx-2 my-2 py-2 px-1 alert alert-warning">
                                <div class="fs-6">* - данные из путевки</div>
                                <div class="form-floating col-2">
                                    <input type="number"
                                           oninput="updateTicketKilometres()"
                                           min="0"
                                           class="form-control"
                                           id="kilometres_city"
                                           name="kilometres_city"
                                           placeholder="город">
                                    <label for="kilometres_city">
                                        город
                                        <span class="badge bg-warning bg-opacity-10 text-warning ms-1"
                                              data-bs-toggle="tooltip"
                                              title="Участвует в расчете общего нормы км">
                                        <i class="bi bi-calculator-fill"></i>  участвует в расчете
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-2">
                                    <input type="number"
                                           oninput="updateTicketKilometres()"
                                           min="0"
                                           class="form-control"
                                           id="kilometres_trail"
                                           name="kilometres_trail"
                                           placeholder="трасса">
                                    <label for="kilometres_trail">
                                        трасса
                                        <span class="badge bg-warning bg-opacity-10 text-warning ms-1"
                                              data-bs-toggle="tooltip"
                                              title="Участвует в расчете общего нормы км">
                                        <i class="bi bi-calculator-fill"></i>  участвует в расчете
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-2">
                                    <input type="number"
                                           oninput="updateTicketKilometres()"
                                           min="0"
                                           class="form-control"
                                           id="kilometres_ground"
                                           name="kilometres_ground"
                                           placeholder="грунт">
                                    <label for="kilometres_ground">
                                        грунт
                                        <span class="badge bg-warning bg-opacity-10 text-warning ms-1" data-bs-toggle="tooltip"
                                              title="Участвует в расчете общего нормы км">
                                        <i class="bi bi-calculator-fill"></i>  участвует в расчете
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-2">
                                    <input type="number"
                                           oninput="updateTicketKilometres()"
                                           min="0"
                                           class="form-control"
                                           id="kilometres_linear"
                                           name="kilometres_linear"
                                           placeholder="линейная"
                                    >
                                    <label for="kilometres_linear">
                                        линейная
                                        <span class="badge bg-warning bg-opacity-10 text-warning ms-1" data-bs-toggle="tooltip"
                                              title="Участвует в расчете общего нормы км">
                                        <i class="bi bi-calculator-fill"></i>  участвует в расчете
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-2">
                                    <input type="number"
                                           min="0"
                                           class="form-control"
                                           id="kilometres_ticket"
                                           name="kilometres_ticket"
                                           placeholder="км по путевке"
                                           readonly>
                                    <label for="kilometres_ticket">
                                        км по путевке
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1">авто
                                    </span></label>
                                    </label>
                                </div>
                                <div class="form-floating col-2">
                                    <input type="number"
                                           oninput="updateSpent()"
                                           min="0"
                                           class="form-control"
                                           id="ticket_write_off"
                                           name="ticket_write_off"
                                           placeholder="К списанию"
                                    >
                                    <label for="ticket_write_off">
                                        К списанию
                                        <span class="badge bg-info bg-opacity-10 text-info ms-1"
                                              data-bs-toggle="tooltip"
                                              title="Влияет на расход топлива">
                                            <i class="bi bi-calculator-fill"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="row gx-2 my-2 py-2 px-1 alert alert-warning">
                                <div class="fs-6">* - расчет по нормам</div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text"
                                              id="normal_city">
                                                0
                                        </span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_city"
                                                   name="calc_normal_city"
                                                   placeholder="город">
                                            <label for="calc_normal_city">
                                                город
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                          <span class="input-group-text"
                                                id="normal_trail">
                                                0
                                        </span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_trail"
                                                   name="calc_normal_trail"
                                                   placeholder="трасса">
                                            <label for="calc_normal_trail">
                                                трасса
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                          <span class="input-group-text"
                                                id="normal_ground">
                                                0
                                        </span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_ground"
                                                   name="calc_normal_ground"
                                                   placeholder="грунт">
                                            <label for="calc_normal_ground">
                                                грунт
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text"
                                              id="normal_linear">
                                                0
                                        </span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_linear"
                                                   name="calc_normal_linear"
                                                   placeholder="линейная">
                                            <label for="calc_normal_linear">
                                                линейная
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text"
                                              id="normal_cargo">
                                                0
                                        </span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_cargo"
                                                   name="calc_normal_cargo"
                                                   placeholder="груз">
                                            <label for="calc_normal_cargo">
                                                груз
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                           <span class="input-group-text"
                                                 id="normal_pump">
                                                0
                                        </span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_pump"
                                                   name="calc_normal_pump"
                                                   placeholder="насос">
                                            <label for="calc_normal_pump">
                                                насос
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-2 my-2 py-2 px-1 alert alert-warning">
                                <div class="fs-6">* - данные по грузу</div>
                                    <?php
                                    $cargos = [1, 2, 3, 4, 5];
                                    foreach ($cargos as $index) {
                                        ?>
                                        <div class="form-floating col-2">
                                            <input type="number"
                                                   oninput="updateCargo()"
                                                   min="0"
                                                   class="form-control"
                                                   id="cargo_<?= $index ?>"
                                                   name="cargo_<?= $index ?>"
                                                   placeholder="Груз <?= $index ?>">
                                            <label for="cargo_<?= $index ?>">
                                                Груз <?= $index ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning">  <i class="bi bi-calculator-fill"></i>
                                                    участвует в расчете</span>
                                            </label>
                                        </div>
                                        <?php
                                    } ?>
                            </div>
                            <div class="row gx-2 my-2 py-2 px-1 alert alert-warning">
                                <div class="fs-6">* - данные по весу</div>
                                    <?php
                                    foreach ($cargos as $index) {
                                        ?>
                                        <div class="form-floating col-2">
                                            <input type="number"
                                                   oninput="updateWeight()"
                                                   min="0"
                                                   step="0.001"
                                                   class="form-control"
                                                   id="weight_<?= $index ?>"
                                                   name="weight_<?= $index ?>"
                                                   placeholder="Вес <?= $index ?>">
                                            <label for="weight_<?= $index ?>">
                                                Вес <?= $index ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning">  <i class="bi bi-calculator-fill"></i>
                                                    участвует в расчете
                                                </span>
                                            </label>
                                        </div>
                                        <?php
                                    } ?>
                            </div>
                            <div class="row gx-2 my-2 py-2 px-1 alert alert-warning">
                                <div class="fs-6">* - Выполненные работы</div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateCalcNormalsPump()"
                                           min="0"
                                           class="form-control"
                                           id="pump"
                                           name="pump"
                                           placeholder="Моточасы работа насоса">
                                    <label for="pump">Моточасы работа насоса
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                             <i class="bi bi-calculator-fill"></i>
                                            участвует в расчете
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="row gx-2 my-2 py-2 px-1 alert alert-warning">
                                <div class="fs-6">* - Получено горючего </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateTakenFuel()"
                                           min="0"
                                           class="form-control"
                                           id="taken_load_f"
                                           name="taken_load_f"
                                           placeholder="заправка">
                                    <label for="taken_load_f">заправка
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                             <i class="bi bi-calculator-fill"></i>
                                            участвует в расчете
                                        </span>
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateTakenFuel()"
                                           min="0"
                                           class="form-control"
                                           id="taken_load_other_f"
                                           name="taken_load_other_f"
                                           placeholder="заправка др.">
                                    <label for="taken_load_other_f">заправка др.
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                             <i class="bi bi-calculator-fill"></i>
                                            участвует в расчете
                                        </span>
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateSpent()"
                                           min="0"
                                           class="form-control"
                                           id="taken_transferred_f"
                                           name="taken_transferred_f"
                                           placeholder="передано">
                                    <label for="taken_transferred_f">передано
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                             <i class="bi bi-calculator-fill"></i>
                                        </span>
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateTakenFuel()"
                                           min="0"
                                           class="form-control"
                                           id="taken_other_f"
                                           name="taken_other_f"
                                           placeholder="получено др.">
                                    <label for="taken_other_f">получено др.
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                             <i class="bi bi-calculator-fill"></i>
                                            участвует в расчете
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="row gx-2 my-2 py-2 px-1 alert alert-warning">
                                <div class="fs-6">* - Получено масла </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateTakenButter()"
                                           min="0"
                                           class="form-control"
                                           id="taken_load_b"
                                           name="taken_load_b"
                                           placeholder="заправка">
                                    <label for="taken_load_b">заправка
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                             <i class="bi bi-calculator-fill"></i>
                                            участвует в расчете
                                        </span>
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateTakenButter()"
                                           min="0"
                                           class="form-control"
                                           id="taken_load_other_b"
                                           name="taken_load_other_b"
                                           placeholder="заправка др.">
                                    <label for="taken_load_other_b">заправка др.
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                             <i class="bi bi-calculator-fill"></i>
                                            участвует в расчете
                                        </span>
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateSpentButter()"
                                           min="0"
                                           class="form-control"
                                           id="taken_transferred_b"
                                           name="taken_transferred_b"
                                           placeholder="передано">
                                    <label for="taken_transferred_b">передано
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                           <i class="bi bi-calculator-fill"></i>
                                        </span>
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="number"
                                           oninput="updateTakenButter()"
                                           min="0"
                                           class="form-control"
                                           id="taken_other_b"
                                           name="taken_other_b"
                                           placeholder="получено др.">
                                    <label for="taken_other_b">получено др.
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                             <i class="bi bi-calculator-fill"></i>
                                            участвует в расчете
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>Пройдено километров (отработано моточасов)</legend>
                            <div class="row gx-2">
                                <div class="form-floating col-4">
                                    <input type="number"
                                           oninput="updateCargoNo()"
                                           min="0"
                                           class="form-control"
                                           id="cargo"
                                           name="cargo"
                                           placeholder="С грузом">
                                    <label for="cargo">
                                        С грузом
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1" data-bs-toggle="tooltip"
                                              title="Участвует в расчете тонна-километров"></i> расчет
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-4">
                                    <input type="number"
                                           min="0"
                                           class="form-control"
                                           id="cargo-no"
                                           name="cargo_no"
                                           placeholder="Без груза"
                                           >
                                    <label for="cargo-no">
                                        Без груза
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1"
                                              data-bs-toggle="tooltip"
                                              title="Автоматически рассчитывается из показаний спидометра">авто
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-4">
                                    <input type="number"
                                           oninput="updateCargoNo()"
                                           min="0"
                                           class="form-control"
                                           id="kilometres_speedometer"
                                           name="kilometres_speedometer"
                                           placeholder="Всего">
                                    <label for="kilometres_speedometer">Всего
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1"
                                              data-bs-toggle="tooltip"
                                              title="Автоматически рассчитывается из показаний спидометра">авто
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div id="alert_check_kilometres_warning_Match"></div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>Выполненная работа</legend>
                            <div class="row gx-2">
                                <div class="form-floating col-6">
                                    <input type="text"
                                           oninput="updateCompletedWork()"
                                           class="form-control"
                                           id="completed_work"
                                           name="completed_work"
                                           placeholder="Тонн">
                                    <label for="completed_work">
                                        Тонн
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1" data-bs-toggle="tooltip"
                                              title="Сумма весов грузов">
                                       </i> авто
                                    </span>
                                    </label>
                                </div>
                                <div class="form-floating col-6">
                                    <input type="number"
                                           min="0"
                                           class="form-control"
                                           id="completed_work_km"
                                           name="completed_work_km"
                                           placeholder="Тонна-километров"
                                           >
                                    <label for="completed_work_km">
                                        Тонна-километров
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1"
                                              data-bs-toggle="tooltip"
                                              title="Автоматически рассчитывается из показаний спидометра">авто
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>Материальные средства, л</legend>
                            <div class="row gx-2">
                                <div class="form-floating col-3">
                                    <input type="text"
                                           value="<?= $data['maxOpeningBalanceFuel'] ?>"
                                           class="form-control"
                                           id="opening_balance_fuel"
                                           name="opening_balance_fuel"
                                           placeholder="Тонн">
                                    <label for="opening_balance_fuel">
                                        Остаток на начало периода горючего
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="text"
                                           class="form-control"
                                           id="opening_balance_butter"
                                           name="opening_balance_butter"
                                           placeholder="Тонн">
                                    <label for="opening_balance_butter">
                                        Остаток на начало периода масла
                                    </label>
                                </div>
                                <div class="col-3">
                                    <div class="input-group">
                                        <div class="form-floating flex-grow-1">
                                            <input type="text"
                                                   readonly
                                                   class="form-control"
                                                   id="taken_fuel"
                                                   name="taken_fuel"
                                                   placeholder="Тонн"
                                                   value="0">
                                            <label for="taken_fuel">
                                                Получено горючего
                                                <span class="badge bg-success bg-opacity-10 text-success ms-1"
                                                      data-bs-toggle="tooltip"
                                                      title="Автоматический расчет из добавленных заправок">
                                                        авто
                                                    </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="text"
                                           class="form-control"
                                           id="taken_butter"
                                           name="taken_butter"
                                           placeholder="Тонн">
                                    <label for="taken_butter">
                                        Получено масла
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1" data-bs-toggle="tooltip"
                                              title="Автоматический расчет">
                                      авто
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
<ul class="nav nav-tabs" id="fuelTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active"
                                                    id="local-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#local-panel"
                                                    type="button"
                                                    role="tab"
                                                    aria-selected="true">
                                                <i class="bi bi-building"></i> Склад в/ч
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link"
                                                    id="other-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#other-panel"
                                                    type="button"
                                                    role="tab"
                                                    aria-selected="false">
                                                <i class="bi bi-truck"></i> Заправки других в/ч
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link"
                                                    id="places-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#places-panel"
                                                    type="button"
                                                    role="tab"
                                                    aria-selected="false">
                                                <i class="bi bi-geo-alt"></i> Прочие заправки
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link"
                                                    id="butter-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#butter-panel"
                                                    type="button"
                                                    role="tab"
                                                    aria-selected="false">
                                                <i class="bi bi-droplet"></i> Масла
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="fuelTabsContent">
                                        <div class="tab-pane fade show active"
                                              id="local-panel"
                                              role="tabpanel"
                                              aria-labelledby="local-tab">
                                            <div class="card mt-3">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <span><i class="bi bi-list-check"></i> Заправки (склад в/ч)</span>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#fuelModal">
                                                        <i class="bi bi-plus-lg"></i> Добавить
                                                    </button>
                                                </div>
                                                <div class="card-body p-0">
                                                    <ul class="list-group list-group-flush" id="fuelList">
                                                        <li class="list-group-item text-muted text-center py-3">
                                                            Нет добавленных заправок
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade"
                                             id="other-panel"
                                             role="tabpanel"
                                             aria-labelledby="other-tab">
                                            <div class="card mt-3">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <span><i class="bi bi-list-check"></i> Заправки (Заправки других в/ч)</span>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#fuelOtherModal">
                                                        <i class="bi bi-plus-lg"></i> Добавить
                                                    </button>
                                                </div>
                                                <div class="card-body p-0">
                                                    <ul class="list-group list-group-flush" id="fuelOtherList">
                                                        <li class="list-group-item text-muted text-center py-3">
                                                            Нет добавленных заправок
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade"
                                              id="places-panel"
                                              role="tabpanel"
                                              aria-labelledby="places-tab">
                                            <div class="card mt-3">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <span><i class="bi bi-list-check"></i> Заправки (Прочие заправки)</span>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#fuelPlacesModal">
                                                        <i class="bi bi-plus-lg"></i> Добавить
                                                    </button>
                                                </div>
                                                <div class="card-body p-0">
                                                    <ul class="list-group list-group-flush" id="fuelPlacesList">
                                                        <li class="list-group-item text-muted text-center py-3">
                                                            Нет добавленных заправок
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade"
                                              id="butter-panel"
                                              role="tabpanel"
                                              aria-labelledby="butter-tab">
                                            <div class="card mt-3">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <span><i class="bi bi-list-check"></i> Масла</span>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#butterModal">
                                                        <i class="bi bi-plus-lg"></i> Добавить
                                                    </button>
                                                </div>
                                                <div class="card-body p-0">
                                                    <ul class="list-group list-group-flush" id="butterList">
                                                        <li class="list-group-item text-muted text-center py-3">
                                                            Нет добавленных записей
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="alert_check_ticket_writes_Match"></div>
                        </fieldset>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-12">
                        <div class="row gx-2">
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="spent_fuel"
                                       name="spent_fuel"
                                       placeholder="Тонн">
                                <label for="spent_fuel">
                                    Израсходовано горючего
                                    <span class="badge bg-success bg-opacity-10 text-success ms-1" data-bs-toggle="tooltip"
                                          title="Должно совпадать с положенным по норме">
                                        авто
                                    </span>
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="spent_butter"
                                       name="spent_butter"
                                       placeholder="Тонн">
                                <label for="spent_butter">
                                    Израсходовано масла
                                    <span class="badge bg-success bg-opacity-10 text-success ms-1" data-bs-toggle="tooltip"
                                          title="Должно совпадать с положенным по норме">
                                        авто
                                    </span>
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="normal_fuel"
                                       name="normal_fuel"
                                       placeholder="Тонн">
                                <label for="normal_fuel">
                                    Положено по норме горючего
                                    <span class="badge bg-success bg-opacity-10 text-success ms-1" data-bs-toggle="tooltip"
                                          title="Автоматический расчет">
                                      авто
                                    </span>
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="normal_butter"
                                       name="normal_butter"
                                       placeholder="Тонн">
                                <label for="normal_butter">
                                    Положено по норме масла
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-12">
                        <div class="row gx-2">
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="closing_balance_fuel"
                                       name="closing_balance_fuel"
                                       placeholder="Тонн">
                                <label for="closing_balance_fuel">
                                    Остаток на конец периода горючего
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="closing_balance_butter"
                                       name="closing_balance_butter"
                                       placeholder="Тонн">
                                <label for="closing_balance_butter">
                                    Остаток на конец периода масла
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="saving_fuel"
                                       name="saving_fuel"
                                       placeholder="Тонн">
                                <label for="saving_fuel">
                                    Экономия горючего
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="saving_butter"
                                       name="saving_butter"
                                       placeholder="Тонн">
                                <label for="saving_butter">
                                    Экономия масла
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-12">
                        <div class="row gx-2">
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="excessive_fuel"
                                       name="excessive_fuel"
                                       placeholder="Тонн">
                                <label for="excessive_fuel">
                                    Перерасход горючего
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="excessive_butter"
                                       name="excessive_butter"
                                       placeholder="Тонн">
                                <label for="excessive_butter">
                                    Перерасход масла
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="/military-ticket/<?= $data['idMachines'] ?? $data['machine_id'] ?>/<?= $data['month'] ?>/<?= $data['year'] ?>"
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Назад
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Создать
                    </button>
                </div>
            </form>
            <?php unset($_SESSION['old']); ?>
        </div>
    </div>
</div>
<?php
    require_once __DIR__ . "/modals/MilitaryLocalStock.php";
    require_once __DIR__ . "/modals/MilitaryOtherStock.php";
    require_once __DIR__ . "/modals/MilitaryPlacesStock.php";
    require_once __DIR__ . "/modals/MilitaryButterStock.php";
?>
<script>
    // Переменные для модального окна удаления
    let deleteFuelId = null;
    let deleteFuelOtherId = null;
    let deleteFuelPlacesId = null;
    let deleteButterId = null;

    document.addEventListener('DOMContentLoaded', function () {
        loadNorms(<?= json_encode($data['MilitaryNorm']) ?>);
        document.getElementById('m_norm').addEventListener('change', loadNorms(<?= json_encode($data['MilitaryNorm']) ?>));

        // Загружаем существующие заправки
        loadFuelRecords();
        loadFuelOtherRecords();
        loadFuelPlacesRecords();
        loadButterRecords();

        // Настройка модального окна удаления (local)
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                deleteFuelId = button.getAttribute('data-fuel-id');
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (deleteFuelId) {
                    removeFuelRecord(deleteFuelId);
                    const modal = bootstrap.Modal.getInstance(deleteModal);
                    modal.hide();
                }
            });
        }

        // Настройка модального окна удаления (other)
        const deleteOtherModal = document.getElementById('deleteConfirmModalOther');
        if (deleteOtherModal) {
            deleteOtherModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                deleteFuelOtherId = button.getAttribute('data-fuel-other-id');
            });

            document.getElementById('confirmDeleteOtherBtn').addEventListener('click', function() {
                if (deleteFuelOtherId) {
                    removeFuelOtherRecord(deleteFuelOtherId);
                    const modal = bootstrap.Modal.getInstance(deleteOtherModal);
                    modal.hide();
                }
            });
        }

        // Настройка модального окна удаления (places)
        const deletePlacesModal = document.getElementById('deleteConfirmModalPlaces');
        if (deletePlacesModal) {
            deletePlacesModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                deleteFuelPlacesId = button.getAttribute('data-fuel-places-id');
            });

            document.getElementById('confirmDeletePlacesBtn').addEventListener('click', function() {
                if (deleteFuelPlacesId) {
                    removeFuelPlacesRecord(deleteFuelPlacesId);
                    const modal = bootstrap.Modal.getInstance(deletePlacesModal);
                    modal.hide();
                }
            });
        }

        // Настройка модального окна удаления (butter)
        const deleteButterModal = document.getElementById('deleteConfirmModalButter');
        if (deleteButterModal) {
            deleteButterModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                deleteButterId = button.getAttribute('data-butter-id');
            });

            document.getElementById('confirmDeleteButterBtn').addEventListener('click', function() {
                if (deleteButterId) {
                    removeButterRecord(deleteButterId);
                    const modal = bootstrap.Modal.getInstance(deleteButterModal);
                    modal.hide();
                }
            });
        }
    });
</script>