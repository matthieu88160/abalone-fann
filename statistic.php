<?php

$dataSet = unserialize(file_get_contents('serializedData.ser'));

function getMinAge($dataSet) {
    return min(
        array_map(function ($row){
            return $row[8];
        }, $dataSet)
    );
}
function getMaxAge($dataSet) {
    return max(
        array_map(function ($row){
            return $row[8];
        }, $dataSet)
    );
}
$minAge = getMinAge($dataSet);
$maxAge = getMaxAge($dataSet);

function ageByIndex(array $dataSet, $index) {
    $dataSample = [];

    foreach ($dataSet as $dataRow) {
        $diameter = $dataRow[$index] * 1000;
        $age = $dataRow[8];

        if (!isset($dataSample[$diameter])) {
            $dataSample[$diameter] = [];
        }
        if (!isset($dataSample[$diameter][$age])) {
            $dataSample[$diameter][$age] = 0;
        }
        $dataSample[$diameter][$age]++;
    }

    return $dataSample;
}
function byIndex(array $dataSet, $index) {
    $dataSample = [];

    foreach ($dataSet as $dataRow) {
        $diameter = $dataRow[$index] * 1000;
        $age = $dataRow[8];

        if (!isset($dataSample[$diameter])) {
            $dataSample[$diameter] = ['min' => null, 'max' => null];
        }

        if ($dataSample[$diameter]['min'] > $age || $dataSample[$diameter]['min'] === null) {
            $dataSample[$diameter]['min'] = $age;
        }
        if ($dataSample[$diameter]['max'] < $age || $dataSample[$diameter]['max'] === null) {
            $dataSample[$diameter]['max'] = $age;
        }
    }

    return $dataSample;
}
function calcArrayInterval(array $dataRow) {
    return max($dataRow) - min($dataRow);
}
function calcInterval(array $dataset) {
    return array_sum(array_values($dataset)) / count($dataset);
}
function getInterval(array $dataSet, $index) {
    return calcInterval(array_map('calcArrayInterval', byIndex($dataSet, $index)));
}

$length = calcInterval(array_map('calcArrayInterval', byIndex($dataSet, 1)));
$diameter = calcInterval(array_map('calcArrayInterval', byIndex($dataSet, 2)));
$height = calcInterval(array_map('calcArrayInterval', byIndex($dataSet, 3)));
$wholeHeight = calcInterval(array_map('calcArrayInterval', byIndex($dataSet, 4)));
$shuckedWeight = calcInterval(array_map('calcArrayInterval', byIndex($dataSet, 5)));
$visceralWeight = calcInterval(array_map('calcArrayInterval', byIndex($dataSet, 6)));
$shellWeight = calcInterval(array_map('calcArrayInterval', byIndex($dataSet, 7)));

function calcPercent(array $dataRow) {
    $sum = array_sum(array_values($dataRow));
    foreach ($dataRow as $age => $count) {
        $dataRow[$age] = $count * (100 / $sum);
    }

    return $dataRow;
}

$lengthSet = ageByIndex($dataSet, 1);
$diameterSet = ageByIndex($dataSet, 2);
$heightSet = ageByIndex($dataSet, 3);
$wholeWeightSet = ageByIndex($dataSet, 4);

function getAgeSet($row, $wholeHeightSet) {
    $arrayResult = [];
    $wholeHeightSet = $wholeHeightSet[$row[4] * 1000];

    foreach ($wholeHeightSet as $age => $count) {
        $arrayResult[$age] = $count;
    }
    $arrayResult[$row[8]] += 2;
    return $arrayResult;
}

function getRowStats($row, $wholeWeightSet, $minAge, $maxAge) {
    $ageSet = getAgeSet($row, $wholeWeightSet);

    $result = calcPercent($ageSet) + array_fill_keys(range($minAge, $maxAge), 0);
    ksort($result);
    return $result;
}
