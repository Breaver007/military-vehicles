<div class="container-fluid py-5 my-4">
    <div class="card">
        <div class="card-header">
            <h3>Редактировать карточку №<?= htmlspecialchars($ticket['ticket_number'] ?? $ticket['id']) ?></h3>
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

            <form action="/military-ticket/update/<?= $data['idMachines'] ?? $ticket['machine_id'] ?>/<?= $data['month'] ?? $ticket['month'] ?>/<?= $data['year'] ?? $ticket['year'] ?>/<?= $ticket['id'] ?>" method="POST">
                <input type="hidden" name="temp_id" value="<?= $temp_id ?? '' ?>">
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
                                <label for="m_model_machine">Машина</label>
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
                                <label for="year">Год</label>
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
                                <label for="month">месяц</label>
                            </div>
                            <div class="form-floating col-3 my-2">
                                <div id="military-norms-data"
                                     data-norms='<?= json_encode($MilitaryNorm, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
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
                                           value="<?= $_SESSION['old']['data_ticket'] ?? $ticket['data_ticket'] ?? '' ?>"
                                           placeholder="Дата">
                                    <label for="data_ticket">Дата</label>
                                </div>
                                <div class="form-floating col-6">
                                    <input type="number"
                                           class="form-control"
                                           id="number_ticket"
                                           name="number_ticket"
                                           placeholder="номер"
                                           value="<?= $_SESSION['old']['number_ticket'] ?? $ticket['number_ticket'] ?? '' ?>">
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
                                           min="<?= $data['maxKilometres'] ?? 0 ?>"
                                           value="<?= $_SESSION['old']['kilometres_speedometer_start'] ?? $ticket['kilometres_speedometer_start'] ?? '' ?>">
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
                                           value="<?= $_SESSION['old']['kilometres_speedometer_end'] ?? $ticket['kilometres_speedometer_end'] ?? '' ?>">
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
                                           placeholder="Кол-во дней"
                                           value="<?= $_SESSION['old']['day_count'] ?? $ticket['day_count'] ?? '' ?>">
                                    <label for="day_count">Кол-во дней</label>
                                </div>
                                <div class="form-floating col-2">
                                    <select class="form-select"
                                            onchange="loadNorms()"
                                            required
                                            id="m_norm"
                                            name="m_norm"
                                            placeholder="Выбор нормы">
                                        <?php
                                        $selectedNorm = $ticket['m_norm'] ?? '';
                                        if ($MilitaryNorm) {
                                            foreach ($MilitaryNorm as $key => $dataNorm) {
                                                ?>
                                                <option value="<?= $dataNorm['id'] ?>" <?= $dataNorm['id'] == $selectedNorm ? 'selected' : '' ?>>
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
                                           placeholder="город"
                                           value="<?= $_SESSION['old']['kilometres_city'] ?? $ticket['kilometres_city'] ?? '' ?>">
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
                                           placeholder="трасса"
                                           value="<?= $_SESSION['old']['kilometres_trail'] ?? $ticket['kilometres_trail'] ?? '' ?>">
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
                                           placeholder="грунт"
                                           value="<?= $_SESSION['old']['kilometres_ground'] ?? $ticket['kilometres_ground'] ?? '' ?>">
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
                                           value="<?= $_SESSION['old']['kilometres_linear'] ?? $ticket['kilometres_linear'] ?? '' ?>">
                                    <label for="kilometres_linear">
                                        линейная
                                        <span class="badge bg-warning bg-opacity-10 text-warning ms-1"
                                              data-bs-toggle="tooltip"
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
                                           readonly
                                           value="<?= $_SESSION['old']['kilometres_ticket'] ?? $ticket['kilometres_ticket'] ?? '' ?>">
                                    <label for="kilometres_ticket">
                                        км по путевке
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1">авто</span>
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
                                           value="<?= $_SESSION['old']['ticket_write_off'] ?? $ticket['ticket_write_off'] ?? '' ?>">
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
                                        <span class="input-group-text" id="normal_city">0</span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_city"
                                                   name="calc_normal_city"
                                                   placeholder="город"
                                                   value="<?= $_SESSION['old']['calc_normal_city'] ?? $ticket['calc_normal_city'] ?? '' ?>">
                                            <label for="calc_normal_city">
                                                город
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text" id="normal_trail">0</span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_trail"
                                                   name="calc_normal_trail"
                                                   placeholder="трасса"
                                                   value="<?= $_SESSION['old']['calc_normal_trail'] ?? $ticket['calc_normal_trail'] ?? '' ?>">
                                            <label for="calc_normal_trail">
                                                трасса
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text" id="normal_ground">0</span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_ground"
                                                   name="calc_normal_ground"
                                                   placeholder="грунт"
                                                   value="<?= $_SESSION['old']['calc_normal_ground'] ?? $ticket['calc_normal_ground'] ?? '' ?>">
                                            <label for="calc_normal_ground">
                                                грунт
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text" id="normal_linear">0</span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_linear"
                                                   name="calc_normal_linear"
                                                   placeholder="линейная"
                                                   value="<?= $_SESSION['old']['calc_normal_linear'] ?? $ticket['calc_normal_linear'] ?? '' ?>">
                                            <label for="calc_normal_linear">
                                                линейная
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text" id="normal_cargo">0</span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_cargo"
                                                   name="calc_normal_cargo"
                                                   placeholder="груз"
                                                   value="<?= $_SESSION['old']['calc_normal_cargo'] ?? $ticket['calc_normal_cargo'] ?? '' ?>">
                                            <label for="calc_normal_cargo">
                                                груз
                                                <span class="badge bg-success bg-opacity-10 text-success">авто</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text" id="normal_pump">0</span>
                                        <div class="form-floating">
                                            <input type="number"
                                                   readonly
                                                   min="0"
                                                   class="form-control"
                                                   id="calc_normal_pump"
                                                   name="calc_normal_pump"
                                                   placeholder="насос"
                                                   value="<?= $_SESSION['old']['calc_normal_pump'] ?? $ticket['calc_normal_pump'] ?? '' ?>">
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
                                               placeholder="Груз <?= $index ?>"
                                               value="<?= $_SESSION['old']["cargo_$index"] ?? $ticket["cargo_$index"] ?? '' ?>">
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
                                               placeholder="Вес <?= $index ?>"
                                               value="<?= $_SESSION['old']["weight_$index"] ?? $ticket["weight_$index"] ?? '' ?>">
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
                                           placeholder="Моточасы работа насоса"
                                           value="<?= $_SESSION['old']['pump'] ?? $ticket['pump'] ?? '' ?>">
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
                                           placeholder="заправка"
                                           value="<?= $_SESSION['old']['taken_load_f'] ?? $ticket['taken_load_f'] ?? '' ?>">
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
                                           placeholder="заправка др."
                                           value="<?= $_SESSION['old']['taken_load_other_f'] ?? $ticket['taken_load_other_f'] ?? '' ?>">
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
                                           placeholder="передано"
                                           value="<?= $_SESSION['old']['taken_transferred_f'] ?? $ticket['taken_transferred_f'] ?? '' ?>">
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
                                           placeholder="получено др."
                                           value="<?= $_SESSION['old']['taken_other_f'] ?? $ticket['taken_other_f'] ?? '' ?>">
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
                                           placeholder="заправка"
                                           value="<?= $_SESSION['old']['taken_load_b'] ?? $ticket['taken_load_b'] ?? '' ?>">
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
                                           placeholder="заправка др."
                                           value="<?= $_SESSION['old']['taken_load_other_b'] ?? $ticket['taken_load_other_b'] ?? '' ?>">
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
                                           placeholder="передано"
                                           value="<?= $_SESSION['old']['taken_transferred_b'] ?? $ticket['taken_transferred_b'] ?? '' ?>">
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
                                           placeholder="получено др."
                                           value="<?= $_SESSION['old']['taken_other_b'] ?? $ticket['taken_other_b'] ?? '' ?>">
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
                                           placeholder="С грузом"
                                           value="<?= $_SESSION['old']['cargo'] ?? $ticket['cargo'] ?? '' ?>">
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
                                           value="<?= $_SESSION['old']['cargo_no'] ?? $ticket['cargo_no'] ?? '' ?>">
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
                                           placeholder="Всего"
                                           value="<?= $_SESSION['old']['kilometres_speedometer'] ?? $ticket['kilometres_speedometer'] ?? '' ?>">
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
                                           placeholder="Тонн"
                                           value="<?= $_SESSION['old']['completed_work'] ?? $ticket['completed_work'] ?? '' ?>">
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
                                           value="<?= $_SESSION['old']['completed_work_km'] ?? $ticket['completed_work_km'] ?? '' ?>">
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
                                           class="form-control"
                                           id="opening_balance_fuel"
                                           name="opening_balance_fuel"
                                           placeholder="Тонн"
                                           value="<?= $_SESSION['old']['opening_balance_fuel'] ?? $ticket['opening_balance_fuel'] ?? '' ?>">
                                    <label for="opening_balance_fuel">
                                        Остаток на начало периода горючего
                                    </label>
                                </div>
                                <div class="form-floating col-3">
                                    <input type="text"
                                           class="form-control"
                                           id="opening_balance_butter"
                                           name="opening_balance_butter"
                                           placeholder="Тонн"
                                           value="<?= $_SESSION['old']['opening_balance_butter'] ?? $ticket['opening_balance_butter'] ?? '' ?>">
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
                                                   value="<?= $_SESSION['old']['taken_fuel'] ?? $ticket['taken_fuel'] ?? '' ?>">
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
                                           placeholder="Тонн"
                                           value="<?= $_SESSION['old']['taken_butter'] ?? $ticket['taken_butter'] ?? '' ?>">
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
                                                        <li class="list-group-item text-muted text-center py-3">Загрузка...</li>
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
                                                        <li class="list-group-item text-muted text-center py-3">Загрузка...</li>
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
                                                        <li class="list-group-item text-muted text-center py-3">Загрузка...</li>
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
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['spent_fuel'] ?? $ticket['spent_fuel'] ?? '' ?>">
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
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['spent_butter'] ?? $ticket['spent_butter'] ?? '' ?>">
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
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['normal_fuel'] ?? $ticket['normal_fuel'] ?? '' ?>">
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
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['normal_butter'] ?? $ticket['normal_butter'] ?? '' ?>">
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
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['closing_balance_fuel'] ?? $ticket['closing_balance_fuel'] ?? '' ?>">
                                <label for="closing_balance_fuel">
                                    Остаток на конец периода горючего
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="closing_balance_butter"
                                       name="closing_balance_butter"
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['closing_balance_butter'] ?? $ticket['closing_balance_butter'] ?? '' ?>">
                                <label for="closing_balance_butter">
                                    Остаток на конец периода масла
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="saving_fuel"
                                       name="saving_fuel"
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['saving_fuel'] ?? $ticket['saving_fuel'] ?? '' ?>">
                                <label for="saving_fuel">
                                    Экономия горючего
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="saving_butter"
                                       name="saving_butter"
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['saving_butter'] ?? $ticket['saving_butter'] ?? '' ?>">
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
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['excessive_fuel'] ?? $ticket['excessive_fuel'] ?? '' ?>">
                                <label for="excessive_fuel">
                                    Перерасход горючего
                                </label>
                            </div>
                            <div class="form-floating col-3">
                                <input type="text"
                                       class="form-control"
                                       id="excessive_butter"
                                       name="excessive_butter"
                                       placeholder="Тонн"
                                       value="<?= $_SESSION['old']['excessive_butter'] ?? $ticket['excessive_butter'] ?? '' ?>">
                                <label for="excessive_butter">
                                    Перерасход масла
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="/military-ticket/<?= $data['idMachines'] ?? $ticket['machine_id'] ?>/<?= $data['month'] ?? $ticket['month'] ?>/<?= $data['year'] ?? $ticket['year'] ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                        <a href="/military-ticket/delete/<?= $data['idMachines'] ?? $ticket['machine_id'] ?>/<?= $data['month'] ?? $ticket['month'] ?>/<?= $data['year'] ?? $ticket['year'] ?>/<?= $ticket['id']?>" class="btn btn-danger">
                            <i class="bi bi-x-square"></i> Удалить
                        </a>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Обновить
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
?>
<script>
    let deleteFuelId = null;
    let deleteFuelOtherId = null;
    let deleteFuelPlacesId = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadNorms(<?= json_encode($MilitaryNorm) ?>);
        document.getElementById('m_norm').addEventListener('change', loadNorms(<?= json_encode($MilitaryNorm) ?>));
        updateCargoNo();
        updateCompletedWork();

        loadFuelRecords();
        loadFuelOtherRecords();
        loadFuelPlacesRecords();

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
    });
</script>