<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Донесение о наличии и движении ГСМ</title>
    <link rel="stylesheet" href="/public/css/bootstrap.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.2;
            margin: 1.5cm 1cm;
            background: white;
            color: black;
        }

        table {
            border-collapse: collapse;
            width: auto; /* Фиксированная ширина таблицы */
            table-layout: fixed; /* Важно! Фиксирует ширину колонок */
        }

        @media print {
            .no-print {
                display: none;
            }

            .page-break {
                page-break-after: always;
            }
        }

        .print-button {
            display: block;
            width: 200px;
            margin: 10px auto;
            padding: 8px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .table-bordered {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
            padding: 2px 4px;
        }

        .table-bordered th {
            background: #e0e0e0;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .small-text {
            font-size: 7pt;
        }

        .vertical-mode {
            height: 140px;
            width: 20px;
            vertical-align: middle;
        }

        .rotated {
            transform: rotate(180deg);
            writing-mode: vertical-rl;
            /*display: inline-block;*/
        }
    </style>
</head>
<body contenteditable="true">
<div class="container-fluid">
    <button onclick="window.print()" contenteditable="false" class="print-button no-print d-print-none">
        🖨️ Распечатать
    </button>

    <?php
    // === СБОР ДАННЫХ ===
    $fuels = [];
    $fuelIds = [];

    foreach ($data['ModelMachine'] as $machine) {
        if (!empty($machine['m_fuel'])) $fuelIds[] = $machine['m_fuel'];
    }
    $fuelIds = array_unique($fuelIds);

    foreach ($fuelIds as $fuelId) {
        if (isset($data['FuelModel'][$fuelId])) {
            $fuels[$fuelId] = [
                'name' => $data['FuelModel'][$fuelId]['name'],
                'unit' => $data['FuelModel'][$fuelId]['unit'] ?? 'л',
                'opening_balance' => 0,
                'taken_local' => 0,
                'taken_other' => 0,
                'taken_prochee' => 0,
                'spent_fuel' => 0,
                'transferred' => 0,
                'spent_prochee' => 0,
                'closing_balance' => 0,
                'normal_fuel' => 0,
                'saving_fuel' => 0,
                'excessive_fuel' => 0,
            ];
        }
    }
    // Техника
    $machinesData = [];

    foreach ($data['ModelMachineTicket'] as $ticket) {
        $machineId = $ticket['m_model_machine'];
        $fuelId = $data['ModelMachine'][$machineId]['m_fuel'] ?? null;

        if ($fuelId && isset($fuels[$fuelId])) {
            // Топливо
            $fuels[$fuelId]['spent_fuel'] += floatval($ticket['spent_fuel'] ?? 0);
            $fuels[$fuelId]['taken_local'] += floatval($ticket['taken_load_f'] ?? 0);
            $fuels[$fuelId]['taken_other'] += floatval($ticket['taken_other_f'] ?? 0);
            $fuels[$fuelId]['transferred'] += floatval($ticket['taken_transferred_f'] ?? 0);
            $fuels[$fuelId]['normal_fuel'] += floatval($ticket['normal_fuel'] ?? 0);
        }

        // Техника - сбор данных по каждой машине
        if (!isset($machinesData[$machineId])) {
            $machinesData[$machineId] = [
                'name' => $data['ModelMachine'][$machineId]['name'] ?? '',
                'model' => $data['ModelMachine'][$machineId]['model'] ?? '',
                'registr_plate' => $data['ModelMachine'][$machineId]['registr_plate'] ?? '',
                'kilometres' => 0,
                'fuel' => 0,
                'oil' => 0,
            ];
        }
        $machinesData[$machineId]['kilometres'] += floatval($ticket['kilometres_speedometer'] ?? 0);
        $machinesData[$machineId]['fuel'] += floatval($ticket['spent_fuel'] ?? 0);
    }
    // Рассчитываем остальные показатели
    foreach ($fuels as $fuelId => &$fuel) {
        $fuel['taken_total'] = $fuel['taken_local'] + $fuel['taken_other'] + $fuel['taken_prochee'];
        $fuel['spent_total'] = $fuel['spent_fuel'] + $fuel['transferred'] + $fuel['spent_prochee'];
        $fuel['closing_balance'] = $fuel['opening_balance'] + $fuel['taken_total'] - $fuel['spent_total'];
        if ($fuel['spent_fuel'] < $fuel['normal_fuel']) {
            $fuel['saving_fuel'] = $fuel['normal_fuel'] - $fuel['spent_fuel'];
            $fuel['excessive_fuel'] = 0;
        } else {
            $fuel['saving_fuel'] = 0;
            $fuel['excessive_fuel'] = $fuel['spent_fuel'] - $fuel['normal_fuel'];
        }
    }
    unset($fuel);
    ?>

    <!-- Заголовок -->
    <h5 class="text-center fw-bold" style="font-size: 11pt;">ДОНЕСЕНИЕ № ______</h5>
    <p class="text-center small-text">о наличии и движении ГСМ и ТС 1 гр. ЦМТО войсковой части 2187 по состоянию
        на <?= date('d.m.Y', strtotime($data['endDate'] ?? date('Y-m-d'))) ?></p>

    <!-- Таблица 1: Основная -->
    <table class="table-bordered w-100">
        <thead>
        <tr>
            <th rowspan="2" style="width: 25px;">№ п/п</th>
            <th rowspan="2" style="width: 150px;">
                Наименование материальных средств
            </th>
            <th class="vertical-mode" rowspan="2" style="width: 40px;">
                <span class="rotated">Единица измерения</span>
            </th>
            <th class="vertical-mode" rowspan="2" style="width: 30px;">
                <span class="rotated"> Категория</span>
            </th>
            <th class="vertical-mode" rowspan="2" style="width: 50px;">
                <span class="rotated"> Остаток на начало <br> отчетного периода</span>
            </th>

            <th colspan="4">Прибыло</th>
            <th colspan="4">Убыло</th>

            <th class="vertical-mode" rowspan="2" style="width: 50px;">
                <span class="rotated">Остаток на конец <br> отчетного периода</span>
            </th>
            <th class="vertical-mode" rowspan="2" style="width: 50px;">
                <span class="rotated">Положено <br> израсходовать <br> по норме</span>
            </th>
            <th class="vertical-mode" rowspan="2" style="width: 40px;">
                <span class="rotated">Экономия</span>
            </th>
            <th class="vertical-mode" rowspan="2" style="width: 40px;">
                <span class="rotated">Перерасход</span>
            </th>
        </tr>
        <tr>
            <th class="vertical-mode"><span class="rotated">со склада <br> воинской части</th>
            <th class="vertical-mode"><span class="rotated">из других <br> воинских частей  <br> (подразделений)</span></th>
            <th class="vertical-mode"><span class="rotated">прочий приход</span></th>
            <th class="vertical-mode"><span class="rotated">Всего</span></th>
            <th class="vertical-mode"><span class="rotated">фактически сожжено в  двигателях, <br> израсходовано  <br> на эксплуатационные  <br> нужд</span>
            </th>
            <th class="vertical-mode"><span class="rotated">передано другим  <br> воинским частям <br> (подразделениям)</span></th>
            <th class="vertical-mode"><span class="rotated">прочий расход</span></th>
            <th class="vertical-mode"><span class="rotated">Всего</span></th>
        </tr>
        </thead>
        <tbody>
        <?php $rowNum = 1; ?>
        <?php foreach ($fuels as $fuel): ?>
            <tr class="text-center">
                <td class="text-center"><?= $rowNum++ ?></td>
                <td class="text-start"><?= $fuel['name'] ?></td>
                <td class="text-center"><?= $fuel['unit'] ?></td>
                <td class="text-center">-</td>
                <td><?= number_format($fuel['opening_balance'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['taken_local'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['taken_other'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['taken_prochee'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['taken_total'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['spent_fuel'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['transferred'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['spent_prochee'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['spent_total'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['closing_balance'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['normal_fuel'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['saving_fuel'], 1, '.', ' ') ?? '-' ?></td>
                <td><?= number_format($fuel['excessive_fuel'], 1, '.', ' ') ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>


        <tr class="text-center">
            <td><?= $rowNum++ ?></td>
            <td class="text-start">Топливная карта</td>
            <td>шт.</td>
            <td>-</td>
            <td><?= number_format(1, 1, '.', ' ') ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td><?= number_format(1, 1, '.', ' ') ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
        </tbody>
    </table>

    <p class="small-text mt-2">Итого наименований: <?= $rowNum - 1 ?> (<?= num2text($rowNum - 1) ?>)</p>

    <?php
    $gr6Fuel = [];
    $gr7Fuel = [];
    $gr8Fuel = [];
    $gr6FuelSum = [];
    $gr7FuelSum = [];
    $gr8FuelSum = [];

    foreach ($fuels as $idF => $fuel) {
        foreach ($data['ModelMachineTicket'] as $idMMT => $modelMachineTicket) {
            if ($data['ModelMachine'][$modelMachineTicket['m_model_machine']]['m_fuel'] == $idF) {
                if ($data['ticketLocalModel'][$idMMT]) {
                    foreach ($data['ticketLocalModel'][$idMMT] as $key => $ticketLocalModel) {
                        $mt_local_id = $ticketLocalModel['mt_local_id'];
                        if (!isset($gr6Fuel[$mt_local_id])) {
                            $gr6Fuel[$mt_local_id][$idF] = 0;
                        }
                        if (!isset($gr6FuelSum[$idF])) {
                            $gr6FuelSum[$idF] = 0;
                        }
                        $gr6Fuel[$mt_local_id][$idF] += $ticketLocalModel['value'];
                        $gr6FuelSum[$idF] += $ticketLocalModel['value'];
                    }
                    foreach ($data['ticketOtherModel'][$idMMT] as $key => $ticketOtherModel) {
                        $mt_other_id = $ticketOtherModel['mt_other_id'];
                        if (!isset($gr7Fuel[$mt_other_id])) {
                            $gr7Fuel[$mt_other_id][$idF] = 0;
                        }
                        if (!isset($gr7FuelSum[$idF])) {
                            $gr7FuelSum[$idF] = 0;
                        }
                        $gr7Fuel[$mt_other_id][$idF] += $ticketOtherModel['value'];
                        $gr7FuelSum[$idF] += $ticketOtherModel['value'];
                    }
                    foreach ($data['ticketPlacesModel'][$idMMT] as $key => $ticketPlacesModel) {
                        $mt_places_id = $ticketPlacesModel['mt_places_id'];
                        if (!isset($gr8Fuel[$mt_places_id])) {
                            $gr8Fuel[$mt_places_id][$idF] = 0;
                        }
                        if (!isset($gr8FuelSum[$idF])) {
                            $gr8FuelSum[$idF] = 0;
                        }
                        $gr8Fuel[$mt_places_id][$idF] += $ticketPlacesModel['value'];
                        $gr8FuelSum[$idF] += $ticketPlacesModel['value'];
                    }
                }
            }
        }
    }
    ?>
    <!-- Таблица 2: Сводка по документам -->
    <table class="table-bordered w-100 mt-2">
        <thead>
        <tr>
            <th rowspan="2" class="vertical-mode" style="width: 120px;"><span class="rotated">Наименование графы</span></th>
            <th rowspan="2" style="width: 100px;">Откуда прибыло, куда убыло</th>
            <th rowspan="2" style="width: 100px;">Наименование документа</th>
            <th rowspan="2" style="width: 155px;">№, дата документа</th>
            <th colspan="<?= count($fuels) ?>">Наименование и количество ГСМ, ТС</th>
        </tr>
        <tr>
            <?php
            foreach ($fuels as $key => $fuel) {
                ?>
                <th class="vertical-mode"><span class="rotated"><?= $fuel['name'] ?></span></th>
                <?php
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td rowspan="<?= count($gr6Fuel) + 1 ?>" class="text-center">6</td>
        </tr>
        <?php
        if ($gr6Fuel) {
            foreach ($gr6Fuel as $mt_other_id => $idFuels) {
                ?>
                <tr>
                    <td><?= $data['localStock'][$mt_other_id]['name'] ?></td>
                    <td><?= $data['localStock'][$mt_other_id]['name_document'] ?></td>
                    <td><?= $data['localStock'][$mt_other_id]['number_document'] ?></td>
                    <?php
                    foreach ($fuels as $key => $fuel) {
                        ?>
                        <td class="text-center">
                            <?= $idFuels[$key] ?? 0 ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        }
        ?>
        <tr class="fw-bold">
            <td colspan="4">Всего по графе 6</td>
            <?php
            foreach ($fuels as $key => $fuel) {
                ?>
                <td class="text-center">
                    <?= $gr6FuelSum[$key] ?? 0 ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td rowspan="<?= count($gr7Fuel) + 1 ?>" class="text-center">7</td>
        </tr>
        <?php
        if ($gr7Fuel) {
            foreach ($gr7Fuel as $mt_places_id => $idFuels) {
                ?>
                <tr>
                    <td><?= $data['fuelOtherPlace'][$mt_places_id]['name'] ?></td>
                    <td><?= $data['fuelOtherPlace'][$mt_places_id]['name_document'] ?></td>
                    <td><?= $data['fuelOtherPlace'][$mt_places_id]['number_document'] ?></td>
                    <?php
                    foreach ($fuels as $key => $fuel) {
                        ?>
                        <td class="text-center">
                            <?= $idFuels[$key] ?? 0 ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        }?>
        <tr class="fw-bold">
            <td colspan="4">Всего по графе 7</td>
            <?php
            foreach ($fuels as $key => $fuel) {
                ?>
                <td class="text-center">
                    <?= $gr7FuelSum[$key] ?? 0 ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td rowspan="<?= count($gr8Fuel) + 1 ?>" class="text-center">8</td>
        </tr>
        <?php
        if ($gr8Fuel) {
        foreach ($gr8Fuel as $mt_local_id => $idFuels) {
        ?>
        <tr>
            <td><?= $data['otherStock'][$mt_local_id]['name'] ?></td>
            <td><?= $data['otherStock'][$mt_local_id]['name_document'] ?></td>
            <td><?= $data['otherStock'][$mt_local_id]['number_document'] ?></td>
            <?php
            foreach ($fuels as $key => $fuel) {
                ?>
                <td class="text-center">
                    <?= $idFuels[$key] ?? 0 ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
            }
        }?>
        <tr class="fw-bold">
            <td colspan="4">Всего по графе 8</td>
            <?php
            foreach ($fuels as $key => $fuel) {
                ?>
                <td class="text-center">
                    <?= $gr8FuelSum[$key] ?? 0 ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td class="text-center">10</td>
            <td>Фактический расход</td>
            <td>Путевой лист</td>
            <td class="small">
                <?php
                foreach ($machinesData as $idMachines => $dataMachines) {
                    ?>
                    <p><span class="fw-bold"><?=$dataMachines['name']?></span><?= ": " . $dataMachines['registr_plate']?></p>
                    <?php
                    }
                ?>
            </td>
            <td>
                <?php foreach ($fuels as $f) echo $f['name'] . ': ' . number_format($f['spent_fuel'], 1, '.', ' ') . '; '; ?>
            </td>
        </tr>
        <tr class="fw-bold">
            <td colspan="5" class="text-right">Всего по графе 10:</td>
            <td>
                <?php foreach ($fuels as $f) echo $f['name'] . ': ' . number_format($f['spent_fuel'], 1, '.', ' ') . '; '; ?>
            </td>
        </tr>
        <tr>
            <td class="text-center">11</td>
            <td>Передано другим в/ч</td>
            <td>-</td>
            <td>Накладная</td>
            <td>№ ___ от __.__.20__</td>
            <td>
                <?php foreach ($fuels as $f) if ($f['transferred'] > 0) echo $f['name'] . ': ' . number_format($f['transferred'], 1, '.', ' ') . '; '; ?>
            </td>
        </tr>
        <tr>
            <td class="text-center">12</td>
            <td>Прочий расход</td>
            <td>Замена масла</td>
            <td>АКТ</td>
            <td>__.__.20__</td>
            <td>М-8В: <?= number_format($oils['М-8В']['spent'], 1, '.', ' ') ?></td>
        </tr>
        </tbody>
    </table>

    <!-- Таблица 3: Расход по технике -->
    <h6 class="text-center fw-bold mt-4">РАСХОД ПО ТЕХНИКЕ</h6>
    <table class="table-bordered w-100 mt-2">
        <thead>
        <tr>
            <th style="width: 25px;">№ п/п</th>
            <th>Наименование техники</th>
            <th>Марка, модель</th>
            <th>Номер техники</th>
            <th>Отработано км/ч</th>
            <th>АИ-92</th>
            <th>ДТ-З</th>
            <th>АИ-95</th>
            <th>М-8В</th>
        </tr>
        </thead>
        <tbody>
        <?php $mNum = 1; ?>
        <?php foreach ($machinesData as $machine): ?>
            <tr>
                <td class="text-center"><?= $mNum++ ?></td>
                <td><?= $machine['name'] ?></td>
                <td><?= $machine['model'] ?></td>
                <td><?= $machine['registr_plate'] ?></td>
                <td class="text-right"><?= number_format($machine['kilometres'], 0, '.', ' ') ?></td>
                <td class="text-right"><?= number_format($machine['fuel'], 1, '.', ' ') ?></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        <?php endforeach; ?>
        <tr class="fw-bold">
            <td colspan="4" class="text-right">Всего:</td>
            <td class="text-right"><?= number_format(array_sum(array_column($machinesData, 'kilometres')), 0, '.', ' ') ?></td>
            <td class="text-right"><?= number_format(array_sum(array_column($machinesData, 'fuel')), 1, '.', ' ') ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
        </tbody>
    </table>

    <!-- Подписи -->
    <div class="row mt-4">
        <div class="col-2 fw-bold">Командир подразделения:</div>
        <div class="col-4">лейтенант</div>
        <div class="col-6 border-bottom border-black"></div>
    </div>
    <div class="row small-text">
        <div class="col-2"></div>
        <div class="col-4">(воинские должность и звание)</div>
        <div class="col-6 text-center">(подпись, фамилия)</div>
    </div>

    <div class="mt-3">
        <strong>Заключение:</strong>
    </div>

    <div class="row mt-3">
        <div class="col-2 fw-bold">Начальник отделения ГСМ:</div>
        <div class="col-4">майор</div>
        <div class="col-6 border-bottom border-black"></div>
    </div>
    <div class="row small-text">
        <div class="col-2"></div>
        <div class="col-4">(воинские должность и звание)</div>
        <div class="col-6 text-center">(подпись, фамилия)</div>
    </div>

    <div class="row mt-3">
        <div class="col-3 text-center border-bottom border-black"></div>
    </div>
    <div class="row small-text">
        <div class="col-3 text-center">(дата)</div>
    </div>
</div>

<?php
function num2text($num)
{
    $words = ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять', 'десять',
        'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать',
        'семнадцать', 'восемнадцать', 'девятнадцать', 'двадцать'];
    if ($num <= 20) return $words[$num];
    return $num;
}

?>

<script>
    <?php if (isset($_GET['autoprint'])): ?>
    window.onload = function () {
        window.print();
    }
    <?php endif; ?>
</script>
</body>
</html>