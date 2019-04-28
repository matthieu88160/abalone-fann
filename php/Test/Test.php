<?php
/**
 * This file is part of the abalone_fann project.
 * As each files provides by the CSCFA, this file is licensed
 * under the MIT license.
 * PHP version 5.6
 *
 * @category Fann
 * @package  Abalone_fann
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */

use Cscfa\Abalone\Ann\Ann;

require_once __DIR__ . '/../vendor/autoload.php';

if (in_array('-h', $argv)) {
    echo sprintf('Test.php [precision] [allowed interval] %s', chr(0x0A));
    return;
}

$file = new SplFileInfo(__DIR__ . '/../ann.net');
$ann = new Ann(null, $file);

$dataSet = unserialize(file_get_contents(__DIR__ . '/../serializedData.ser'));

$maxRange = 0;
$minRange = 29;
$ranges = [];

$guessingInterval = 2;
if (isset($argv[1])) {
    $guessingInterval = intval($argv[1]);
}
echo sprintf('Accuracy precision : %f%%%s', 100 / $guessingInterval, chr(0x0A));

$allowedInterval = 3;
if (isset($argv[2])) {
    $allowedInterval = intval($argv[2]);
}

$accuracy = array_fill(1, 29, [
    'total' => 0,
    'count' => 0,
    'min' => 29,
    'max' => 0
]);
unset($accuracy[28]);
$success = 0;
foreach (array_values($dataSet) as $key => $dataRow) {
    $inputData = [
        $dataRow[0] == 0 ? 1 : 0,
        $dataRow[0] == 1 ? 1 : 0,
        $dataRow[0] == 2 ? 1 : 0,
        $dataRow[1],
        $dataRow[2],
        $dataRow[3],
        $dataRow[4]
    ];

    $output = $ann->run($inputData);

    $maxAge = array_search(max($output), $output);
    $realAge = $dataRow[8];
    $ageRange = abs($realAge - $maxAge);
    $ranges[] = $ageRange;

    $accuracy[$realAge]['total']++;
    if ($ageRange <= $allowedInterval) {
        $accuracy[$realAge]['count']++;
    }

    if ($accuracy[$realAge]['max'] < $maxAge) {
        $accuracy[$realAge]['max'] = $maxAge;
    }
    if ($accuracy[$realAge]['min'] > $maxAge) {
        $accuracy[$realAge]['min'] = $maxAge;
    }
    if ($maxRange < $ageRange) {
        $maxRange = $ageRange;
    }
    if ($minRange > $ageRange) {
        $minRange = $ageRange;
    }

    $maxGuess = max($output);
    $age = array_filter($output, function ($data) use ($maxGuess, $guessingInterval){
        return $data > ($maxGuess / $guessingInterval);
    });
    if (in_array($dataRow[8], array_keys($age))) {
        $success++;
    }
}
echo sprintf('Accuracy : %s%%%s', ($success * (100 / count($dataSet))), chr(0x0A));
echo sprintf('Max range : +-%d%s', $maxRange, chr(0x0A));
echo sprintf('Min range : +-%d%s', $minRange, chr(0x0A));
echo sprintf('Average range : +-%d%s', (array_sum($ranges) / count($ranges)), chr(0x0A));

$statPercent = 100 / count($ranges);
$rangeStatistic = array_count_values($ranges);
ksort($rangeStatistic);

$sum = 0;
$countSum = 0;
echo chr(0x0A);
foreach ($rangeStatistic as $ageRange => $count) {
    $percent = $count * $statPercent;
    $sum += $percent;
    $countSum += $count;
    echo sprintf(
        'With a precision of %d years: %d (%d / %d, %f%%) -> %f%% / %f%%%s',
        $ageRange,
        $count,
        $countSum,
        count($ranges),
        $percent,
        $sum,
        abs(100 - $sum),
        chr(0x0A)
    );
}

echo chr(0x0A);
echo sprintf('With a precision of %d years:%s', $allowedInterval, chr(0x0A));
$accuracy = array_map(function ($row){
    if ($row['total'] == 0) {
        return [100, 0, $row['min'], $row['max']];
    }
    return [$row['count'] * (100 / $row['total']), $row['total'], $row['min'], $row['max']];
}, $accuracy);
foreach ($accuracy as $age => $precision) {
    echo sprintf(
        'For an age of %d, the precision is %f%% on %d characters [%d - %d]%s',
        $age,
        $precision[0],
        $precision[1],
        $precision[2],
        $precision[3],
        chr(0x0A)
    );
}
