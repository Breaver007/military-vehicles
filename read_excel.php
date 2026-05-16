<?php
require 'vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('ДОНЕСЕНИЕ.xlsx');

$sheet = $spreadsheet->getSheetByName('январь');
if (!$sheet) $sheet = $spreadsheet->getActiveSheet();

echo "Лист: " . $sheet->getTitle() . "\n";
echo "\nВсе данные:\n";
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();

for ($row = 1; $row <= $highestRow; $row++) {
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
    $cols = [];
    foreach ($rowData[0] as $i => $v) {
        if ($i < 25 && !empty(trim((string)$v))) $cols[] = $v;
    }
    if (!empty($cols)) echo "Строка $row: " . implode(" | ", $cols) . "\n";
}