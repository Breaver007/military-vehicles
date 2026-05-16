<?php
$months = [
    1 => 'Январь',
    2 => 'Февраль',
    3 => 'Март',
    4 => 'Апрель',
    5 => 'Май',
    6 => 'Июнь',
    7 => 'Июль',
    8 => 'Август',
    9 => 'Сентябрь',
    10 => 'Октябрь',
    11 => 'Ноябрь',
    12 => 'Декабрь'
]; ?>
<div class="offcanvas-lg d-lg-none  offcanvas-end"
     tabindex="-1"
     id="offcanvasMilitaryModelMachine"
     aria-labelledby="offcanvasMilitaryModelMachineLabel">
    <div class="offcanvas-body">
        <div class="navbar bg-primary text-light my-2 fs-5 rounded-2 flex-shrink-0">
            <div class="container-fluid">
                Список машин
            </div>
        </div>
        <div class="overflow-auto flex-grow-1" style="min-height: 0">
            <?php
            if ($data['MilitaryUnit']) {
                foreach ($data['MilitaryUnit'] as $index => $Unit) {
                    if ($data['MilitaryModelMachine']) {
                        ?>
                        <div class="list-group">
                            <?php
                            foreach ($data['MilitaryModelMachine'] as $index => $ModelMachine) {
                                ?>
                                <a href="/military-ticket/<?= $ModelMachine['id'] ?>/<?= date('n') ?>/<?= date('Y') ?>"
                                   class="list-group-item list-group-item-action list-group-item-primary">
                                    <?= $ModelMachine['name'] ?>
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
<div class="container-fluid vh-100 overflow-hidden">
    <div class="row h-100 py-5">
        <div class="col-2 d-none d-lg-block border-end h-100 d-flex flex-column overflow-auto">
            <div class="navbar bg-primary text-light my-2 fs-5 rounded-2 flex-shrink-0">
                <div class="container-fluid">
                    Список машин
                </div>
            </div>
            <div class="overflow-auto flex-grow-1" style="min-height: 0">
                <?php
                if ($data['MilitaryUnit']) {
                    foreach ($data['MilitaryUnit'] as $index => $Unit) {
                        if ($data['MilitaryModelMachine']) {
                            ?>
                            <div class="list-group">
                                <?php
                                foreach ($data['MilitaryModelMachine'] as $index => $ModelMachine) {
                                    ?>
                                    <a href="/military-ticket/<?= $ModelMachine['id'] ?>/<?= date('n') ?>/<?= date('Y') ?>"
                                       class="list-group-item list-group-item-action list-group-item-primary">
                                        <?= $ModelMachine['name'] ?>
                                    </a>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
        <?php
        if (isset($data['getModelMachineTicket'])) {
            ?>
            <div class="col-12 col-lg-10 h-100 overflow-auto">
                <div class="navbar bg-primary text-light my-2 fs-5 rounded-2 flex-shrink-0">
                    <div class="container-fluid ">
                        <div id="calendar"
                             class="d-flex align-items-center w-100">
                            <div class="d-flex w-75">
                                <button class="btn btn-light btn-sm"
                                        id="prevMonth"
                                        title="Предыдущий месяц">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <div id="currentMonthYear"
                                     class="text-light text-center fw-bold w-25">
                                </div>
                                <button class="btn btn-light btn-sm"
                                        id="nextMonth"
                                        title="Следующий месяц">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-end w-25">
                                <a href="/military-ticket/export/<?= $data['id'] ?>/<?= $data['month'] ?>/<?= $data['year'] ?>"
                                   class="btn btn-success me-2">
                                    <i class="bi bi-file-excel"></i>
                                    Экспорт в Excel
                                </a>
                                <a href="/military-ticket/print-select/<?= $data['id'] ?>"
                                   class="btn btn-info">
                                    <i class="bi bi-printer"></i>
                                    Печать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <div class="text-center py-1">
                    Эксплуатационная карточка № <?= isset($data['id']) ? $data['id'] : "_____" ?>
                </div>
                <div class="text-center py-1">
                    <?= isset($data['month']) ? $months[$data['month']] : $months[date('n')]; ?>
                    <?= isset($data['year']) ? $data['year'] : date('Y'); ?>
                </div>
                <div class="row row-cols-2 py-1">
                    <div><?= $data['MilitaryUnit'][0]['name'] ?></div>
                    <div class="text-end">
                        <?= isset($data['MilitaryFuel'][$data['MilitaryModelMachine'][$data['id']]['m_fuel']]) ? $data['MilitaryFuel'][$data['MilitaryModelMachine'][$data['id']]['m_fuel']]['name'] : "Не указан вид топлива" ?>
                    </div>
                </div>
                <div class="row row-cols-3">
                    <div><?= isset($data['MilitaryModelMachine'][$data['id']]) ? $data['MilitaryModelMachine'][$data['id']]['name'] : "" ?>
                    </div>
                    <div class="text-center fw-bold">
                        <?= $data['MilitaryModelMachine'][$data['id']]['registr_plate'] ?>
                    </div>
                    <div class="row row-cols-3 text-center">
                        <div class="border border-warning fw-bold">
                            <?= isset($data['getModelMachineTicket'][0]) ? $data['getModelMachineTicket'][0]['kilometres_speedometer_start'] : 0 ?>
                        </div>
                        <div class="border border-warning">
                            <?= array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer')); ?>
                        </div>
                        <div class="border border-warning fw-bold">
                            <?php
                            if (isset(end($data['getModelMachineTicket'])['kilometres_speedometer_start']) && isset(end($data['getModelMachineTicket'])['kilometres_speedometer'])) {
                                $kilometresStart = end($data['getModelMachineTicket'])['kilometres_speedometer_start'];
                                $kilometresDrive = end($data['getModelMachineTicket'])['kilometres_speedometer'];
                                echo $kilometresStart + $kilometresDrive;
                            } else {
                                echo 0;
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header text-end">
                        <button class="btn btn-primary d-lg-none"
                                type="button"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasMilitaryModelMachine"
                                aria-controls="offcanvasMilitaryModelMachine">
                            Выбрать технику
                        </button>
                        <a href="/military-ticket/create/<?= $data['id'] ?>/<?= $data['month'] ?>/<?= $data['year'] ?>"
                           class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Создать запись
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table  table-bordered table-hover table-sm align-middle">
                                <thead class=" ">
                                <tr class="text-center">
                                    <th scope="col" colspan="2">Путевой лист</th>
                                    <th scope="col" colspan="5">Пройдено километров (отработано моточасов)</th>
                                    <th scope="col" colspan="2">Выполненная работа</th>
                                    <th scope="col" colspan="14">Материальные средства, л</th>
                                    <th scope="col" rowspan="3" class="vertical-text-lr" style="height: 180px;">Примечание</th>
                                </tr>
                                <tr class="text-center">
                                    <th rowspan="2" scope="col" class="vertical-text-lr px-4" style="height: 120px;">дата (период)</th>
                                    <th rowspan="2" scope="col" class="vertical-text-lr px-4" style="height: 120px;">номер</th>
                                    <th rowspan="2" scope="col" class="vertical-text-lr px-4" style="height: 120px;">с грузом</th>
                                    <th rowspan="2" scope="col" class="vertical-text-lr px-4" style="height: 120px;">без груза</th>
                                    <th rowspan="2" scope="col" class="vertical-text-lr px-4" style="height: 120px;">всего</th>
                                    <th colspan="2" scope="col">моточасы</th>
                                    <th rowspan="2" scope="col" class="vertical-text-lr px-4" style="height: 120px;">тонн</th>
                                    <th rowspan="2" scope="col" class="vertical-text-lr px-4" style="height: 120px;">тонна- <br> километров
                                    </th>
                                    <th colspan="2" scope="col">Остаток на начало периода</th>
                                    <th colspan="2" scope="col">Получено</th>
                                    <th colspan="2" scope="col">Израсходовано</th>
                                    <th colspan="2" scope="col">Положено по норме</th>
                                    <th colspan="2" scope="col">Остаток на конец периода</th>
                                    <th colspan="2" scope="col">Экономия</th>
                                    <th colspan="2" scope="col">Перерасход</th>
                                </tr>
                                <tr class="text-center">
                                    <th scope="col" class="vertical-text-lr px-4" style="height: 100px;">работа <br> насоса</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">пробег</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">горючего</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">масла</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">горючего</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">масла</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">горючего</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">масла</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">горючего</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">масла</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">горючего</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">масла</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">горючего</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">масла</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">горючего</th>
                                    <th scope="col" class="vertical-text-lr " style="height: 100px;">масла</th>
                                </tr>
                                <tr class="text-center small">
                                    <th>1</th>
                                    <th>2</th>
                                    <th>3</th>
                                    <th>4</th>
                                    <th>5</th>
                                    <th>6</th>
                                    <th>7</th>
                                    <th>8</th>
                                    <th>9</th>
                                    <th>10</th>
                                    <th>11</th>
                                    <th>12</th>
                                    <th>13</th>
                                    <th>14</th>
                                    <th>15</th>
                                    <th>16</th>
                                    <th>17</th>
                                    <th>18</th>
                                    <th>19</th>
                                    <th>20</th>
                                    <th>21</th>
                                    <th>22</th>
                                    <th>23</th>
                                    <th>24</th>
                                </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                <?php
                                foreach ($data['getModelMachineTicket'] as $index => $ticket) {
                                    ?>
                                    <tr class="text-center"
                                        role="button"
                                        onclick="openUrlEditTicket(<?= $data['id'] ?>,<?= $data['month'] ?>,<?= $data['year'] ?>,<?= $ticket['id'] ?>)">
                                        <th scope="row">
                                            <?= date('d.m.y', strtotime($ticket['data_ticket'])) ?>
                                        </th>
                                        <td><?= $ticket['number_ticket'] ?></td>
                                        <td><?= $ticket['cargo'] ?></td>
                                        <td><?= $ticket['kilometres_speedometer'] - $ticket['cargo'] ?></td>
                                        <td><?= $ticket['kilometres_speedometer'] ?></td>
                                        <td><?= $ticket['pump'] ?></td>
                                        <td></td>
                                        <td><?= $ticket['completed_work'] ?></td>
                                        <td><?= round($ticket['completed_work'] * $ticket['cargo'], 0, PHP_ROUND_HALF_UP) ?></td>
                                        <td><?= $ticket['opening_balance_fuel'] ?></td>
                                        <td><?= $ticket['opening_balance_butter'] ?></td>
                                        <td><?= $ticket['taken_fuel'] ?></td>
                                        <td><?= $ticket['taken_butter'] ?></td>
                                        <td><?= $ticket['spent_fuel'] ?></td>
                                        <td><?= $ticket['spent_butter'] ?></td>
                                        <td><?= $ticket['normal_fuel'] ?></td>
                                        <td><?= $ticket['normal_butter'] ?></td>
                                        <td><?= $ticket['closing_balance_fuel'] ?></td>
                                        <td><?= $ticket['closing_balance_butter'] ?></td>
                                        <td><?= $ticket['saving_fuel'] ?></td>
                                        <td><?= $ticket['saving_butter'] ?></td>
                                        <td><?= $ticket['excessive_fuel'] ?></td>
                                        <td><?= $ticket['excessive_butter'] ?></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                                <tfoot class="table-secondary">
                                <tr class="fw-bold text-center">
                                    <td colspan="2">Итого:</td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'cargo')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer')) - array_sum(array_column($data['getModelMachineTicket'], 'cargo')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'pump')) ?></td>
                                    <td></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'completed_work')) ?></td>
                                    <td><?= round(array_sum(array_column($data['getModelMachineTicket'], 'completed_work')) * array_sum(array_column($data['getModelMachineTicket'], 'cargo')), 0, PHP_ROUND_HALF_UP) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'opening_balance_fuel')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'opening_balance_butter')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'taken_fuel')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'taken_butter')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'spent_fuel')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'spent_butter')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'normal_fuel')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'normal_butter')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'closing_balance_fuel')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'closing_balance_butter')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'saving_fuel')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'saving_butter')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'excessive_fuel')) ?></td>
                                    <td><?= array_sum(array_column($data['getModelMachineTicket'], 'excessive_butter')) ?></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="fs-6 fw-bold">
                                Кол-во дней: <?= array_sum(array_column($data['getModelMachineTicket'], 'day_count')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="col-12 col-lg-10 h-100 overflow-auto">
                <div class="alert alert-warning d-flex align-items-center my-2" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <div>
                        Выберите технику!
                    </div>
                </div>
            </div>
            <?php
        } ?>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Безопасное получение данных из PHP
        let idModelMachine = "<?= htmlspecialchars($data['id'] ?? '') ?>";
        let monthParam = "<?= isset($data['month']) ? htmlspecialchars($data['month']) : date('m') ?>";
        let yearParam = <?= isset($data['year']) ? (int)$data['year'] : date('Y') ?>;
        openUrl(idModelMachine, monthParam, yearParam);
    });
</script>