<?php
require_once __DIR__ . '/statistic.php';

$counter = 0;
$dataSet = array_filter($dataSet, function () use (&$counter) {
    return ($counter++) % 2 == 0;
});

$file = fopen(__DIR__ . '/trainingFile.data', 'w+');

$header = sprintf('%d %d %d%s', count($dataSet), 7, 29, chr(0x0A));
fwrite($file, $header, strlen($header));

foreach (array_values($dataSet) as $key => $dataRow) {
    $dataLine = '';
    $stat = getRowStats($dataRow, $wholeWeightSet, $minAge, $maxAge);

    $dataLine =  sprintf(
        '%d %d %d %f %f %f %f%s',
        $dataRow[0] == 0 ? 1 : 0,
        $dataRow[0] == 1 ? 1 : 0,
        $dataRow[0] == 2 ? 1 : 0,
        $dataRow[1],
        $dataRow[2],
        $dataRow[3],
        $dataRow[4],
        chr(0x0A)
    );
    $dataLine .= implode(' ', array_map(function ($data){return $data / 100;}, $stat));
    $dataLine .= chr(0x0A);

    fwrite($file, $dataLine, strlen($dataLine));
}
fclose($file);
