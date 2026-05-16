<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Эксплуатационная карточка</title>
    <link rel="stylesheet" href="/public/css/bootstrap.css">
    <style>
        /* Общие стили */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.2;
            margin: 1.5cm 1cm;
            background: white;
            color: black;
        }

        /* Стили для печати */
        @media print {
            body {
                margin: 1cm;
            }
            .no-print {
                display: none;
            }
            .page-break {
                page-break-after: always;
            }
        }

        /* Вертикальный текст */
        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            height: 100px;
            white-space: nowrap;
            padding: 5px 0;
        }

        /* Кнопка печати */
        .print-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-button:hover {
            background: #0056b3;
        }

        /* Адаптация для экрана */
        @media screen {
            body {
                background: #fff;
                padding: 20px;
            }
            .container {
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                padding: 20px;
            }
        }
    </style>
</head>
<body contenteditable="true">
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
<div class="container">
    <!-- Кнопка печати (не будет видна при печати) -->
    <button onclick="window.print()" contenteditable="false" class="print-button no-print d-print-none">
        🖨️ Распечатать
    </button>

    <!-- Заголовок -->
    <h5 class="text-center">Эксплуатационная карточка № ______ </h5>

    <!-- Месяц и год -->
    <h6 style="text-align: center; margin: 10px 0;">
        <?= $months[date('n', strtotime($data['end_date']))] ?>
        <?= date('Y', strtotime($data['end_date'])) ?>
    </h6>

    <!-- Информация о подразделении и топливе -->
    <div class="row">
        <div class="col-4 text-center border-bottom border-black">
            <strong><?= $data['MilitaryUnit'][0]['name'] ?? '' ?></strong>
        </div>
        <div class="col-4">

        </div>
        <div class="col-4 text-center border-bottom border-black">
            <strong>
                <?= isset($data['MilitaryFuel'][$data['MilitaryModelMachine'][$data['id']]['m_fuel']])
                    ? $data['MilitaryFuel'][$data['MilitaryModelMachine'][$data['id']]['m_fuel']]['name']
                    : "Не указан вид топлива" ?>
            </strong>
        </div>
    </div>
    <div class="row small">
        <div class="col-4 text-center small">
            подразделение
        </div>
        <div class="col-4">

        </div>
        <div class="col-4 text-center small">
            наименование горючего
        </div>
    </div>

    <!-- Модель машины и регистрационный знак -->
    <?php
    $startKm = isset($data['getModelMachineTicket'][0]) ? $data['getModelMachineTicket'][0]['kilometres_speedometer_start'] : 0;
    $totalKm = array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer'));
    $endKm = $startKm + $totalKm;
    ?>
    <div class="row">
        <div class="col-4 text-center border-bottom border-black">
            <strong><?= $data['MilitaryModelMachine'][$data['id']]['name'] ?? '' ?></strong>
        </div>
        <div class="col-1"> </div>
        <div class="col-2 text-center border-bottom border-black">
            <strong><?= $data['MilitaryModelMachine'][$data['id']]['registr_plate'] ?? '' ?></strong>
        </div>
        <div class="col-1"> </div>
        <div class="col-1 text-center border border-black">
            <strong><?= number_format($startKm, 0, '', ' ') ?></strong>
        </div>
        <div class="col-2 text-center border-bottom border-black">
            <strong><?= number_format($totalKm, 0, '', ' ') ?></strong>
        </div>
        <div class="col-1 text-center  border border-black">
            <strong><?= number_format($endKm, 0, '', ' ') ?></strong>
        </div>
    </div>
    <div class="row small">
        <div class="col-4 text-center small">
            тип, марка и модель машины
        </div>
        <div class="col-1"> </div>
        <div class="col-2 text-center small">
            регистр.знак
        </div>
        <div class="col-1"> </div>
        <div class="col-1 text-center small">
            спидометр на начало месяца
        </div>
        <div class="col-2 text-center small">
            пробег за месяц
        </div>
        <div class="col-1 text-center small">
            спидометр на конец месяца
        </div>
    </div>

    <!-- Основная таблица -->
    <table class="table table-sm table-bordered border-black text-center small my-1">
        <thead>
        <tr>
            <th colspan="2">Путевой лист</th>
            <th colspan="5">Пройдено километров (отработано моточасов)</th>
            <th colspan="2">Выполненная работа</th>
            <th colspan="14">Материальные средства, л</th>
            <th rowspan="3">Примечание</th>
        </tr>
        <tr>
            <th rowspan="2">дата (период)</th>
            <th rowspan="2">№</th>
            <th rowspan="2">с грузом</th>
            <th rowspan="2">без груза</th>
            <th rowspan="2">всего</th>
            <th colspan="2">моточасы</th>
            <th rowspan="2">тонн</th>
            <th rowspan="2">тонна-км</th>
            <th colspan="2">Остаток на начало</th>
            <th colspan="2">Получено</th>
            <th colspan="2">Израсходовано</th>
            <th colspan="2">Положено по норме</th>
            <th colspan="2">Остаток на конец</th>
            <th colspan="2">Экономия</th>
            <th colspan="2">Перерасход</th>
        </tr>
        <tr>
            <th>насоса</th>
            <th>пробег</th>
            <th>гор.</th>
            <th>масла</th>
            <th>гор.</th>
            <th>масла</th>
            <th>гор.</th>
            <th>масла</th>
            <th>гор.</th>
            <th>масла</th>
            <th>гор.</th>
            <th>масла</th>
            <th>гор.</th>
            <th>масла</th>
            <th>гор.</th>
            <th>масла</th>
        </tr>
        <tr>
            <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th>
            <th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th>
            <th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th>
            <th>20</th><th>21</th><th>22</th><th>23</th><th>24</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['getModelMachineTicket'] as $ticket): ?>
            <tr>
                <td><?= date('d.m.Y', strtotime($ticket['data_ticket'])) ?></td>
                <td><?= $ticket['number_ticket'] ?></td>
                <td><?= $ticket['cargo'] ?></td>
                <td><?= $ticket['kilometres_speedometer'] - $ticket['cargo'] ?></td>
                <td><?= $ticket['kilometres_speedometer'] ?></td>
                <td><?= $ticket['pump'] ?></td>
                <td></td>
                <td><?= $ticket['completed_work'] ?></td>
                <td><?= round($ticket['completed_work'] * $ticket['cargo'], 0) ?></td>
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
        <?php endforeach; ?>

        <!-- Строка итогов -->
        <tr class="total-row">
            <td colspan="2"><strong>ИТОГО:</strong></td>
            <td><?= array_sum(array_column($data['getModelMachineTicket'], 'cargo')) ?></td>
            <td><?= array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer')) - array_sum(array_column($data['getModelMachineTicket'], 'cargo')) ?></td>
            <td><?= array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer')) ?></td>
            <td><?= array_sum(array_column($data['getModelMachineTicket'], 'pump')) ?></td>
            <td></td>
            <td><?= array_sum(array_column($data['getModelMachineTicket'], 'completed_work')) ?></td>
            <td><?= round(array_sum(array_column($data['getModelMachineTicket'], 'completed_work')) * array_sum(array_column($data['getModelMachineTicket'], 'cargo')), 0) ?></td>
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
        </tbody>
    </table>

    <div class="row">
        <div class="col-6">
            <h6 class="my-1">Эксплуатационные показатели</h6>
            <table class="w-75 table table-sm table-bordered border-black small">
                <tr>
                    <th>Наименование</th>
                    <th>Показатель</th>
                </tr>
                <tr  class="">
                    <td>Вид проведенного ТО</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Пробег после проведенного ТО</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Вид очередного ТО</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Запас хода до очередного ТО</td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="col-6 text-center mt-4">
            Дней в эксплуатации <?= array_sum(array_column($data['getModelMachineTicket'], 'day_count')) ?>
        </div>
    </div>
    <!-- Подписи -->
    <div class="row">
        <div class="col-2 fw-bold">Начальник</div>
        <div class="col-1"></div>
        <div class="col-3 border-bottom border-black"></div>
        <div class="col-1"></div>
        <div class="col-5 text-center border-bottom border-black"></div>
    </div>
    <div class="row small">
        <div class="col-2"></div>
        <div class="col-1"></div>
        <div class="col-3 small text-center">(подразделение)</div>
        <div class="col-1"></div>
        <div class="col-5 small text-center">(воинское звание, подпись, фамилия)</div>
    </div>
    <div class="row mt-4">
        <div class="col-2 fw-bold">Старший техник</div>
        <div class="col-1"></div>
        <div class="col-3 "></div>
        <div class="col-1"></div>
        <div class="col-5 text-center border-bottom border-black"></div>
    </div>
    <div class="row small">
        <div class="col-2"></div>
        <div class="col-1"></div>
        <div class="col-3 small text-center"></div>
        <div class="col-1"></div>
        <div class="col-5 small text-center">(воинское звание, подпись, фамилия)</div>
    </div>
    <!-- Дата -->
    <div class="row mt-4">
        <div class="col-3 text-center border-bottom border-black">

        </div>
    </div>
    <div class="row small">
        <div class="col-3 small text-center">(дата)</div>
    </div>
</div>

<script>
    // Автоматическая печать при загрузке, если передан параметр
    <?php if (isset($_GET['autoprint'])): ?>
    window.onload = function() {
        window.print();
    }
    <?php endif; ?>
</script>
</body>
</html>