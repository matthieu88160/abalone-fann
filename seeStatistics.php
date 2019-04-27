<?php
require_once __DIR__ . '/statistic.php';

echo sprintf(
    'Length : +-%0.1f (%0.2f%% accuracy) - %s not intrusive %s',
    $length / 2,
    100 - ($length * (100 / ($maxAge - $minAge))),
    chr(0x09),
    chr(0x0A)
);
echo sprintf(
    'Diameter : +-%0.1f (%0.2f%% accuracy) - %s not intrusive %s',
    $diameter / 2,
    100 - ($diameter * (100 / ($maxAge - $minAge))),
    chr(0x09),
    chr(0x0A)
);
echo sprintf(
    'Height : +-%0.1f (%0.2f%% accuracy) - %s not intrusive %s',
    $height / 2,
    100 - ($height * (100 / ($maxAge - $minAge))),
    chr(0x09),
    chr(0x0A)
);
echo sprintf(
    'Whole height : +-%0.1f (%0.2f%% accuracy) - not intrusive %s',
    $wholeHeight / 2,
    100 - ($wholeHeight * (100 / ($maxAge - $minAge))),
    chr(0x0A)
);
echo sprintf(
    'Shucked weight : +-%0.1f (%0.2f%% accuracy) %s',
    $shuckedWeight / 2,
    100 - ($shuckedWeight * (100 / ($maxAge - $minAge))),
    chr(0x0A)
);
echo sprintf(
    'Visceral weight : +-%0.1f (%0.2f%% accuracy) %s',
    $visceralWeight / 2,
    100 - ($visceralWeight * (100 / ($maxAge - $minAge))),
    chr(0x0A)
);
echo sprintf(
    'Shell weight : +-%0.1f (%0.2f%% accuracy) %s',
    $shellWeight / 2,
    100 - ($shellWeight * (100 / ($maxAge - $minAge))),
    chr(0x0A)
);

$target = $minAge - 1;

while (++$target <= $maxAge) {
    $maxs = [];
    foreach ($dataSet as $rowVal) {
        if ($rowVal[8] == $target) {
            $maxs[] = $rowVal;
        }
    }
    $arrayStat = [];
    foreach ($maxs as $maxElement) {
        $stat = getRowStats($maxElement, $wholeWeightSet, $minAge, $maxAge);    // <<< Use stat for output result
        $arrayStat[] = array_search(max($stat), $stat);
    }

    if (count($arrayStat) !== 0) {
        $average = array_sum($arrayStat) / count($arrayStat);
        $targetFound = array_filter($arrayStat, function ($value) use ($target){
            return intval($value) == $target;
        });
        $precision = count($targetFound) * (100 / count($arrayStat));
        echo sprintf(
            'Target age : %d, average : %f, min : %f, max : %f, precision : %f%% on %d items %s',
            $target,
            $average,
            min($arrayStat),
            max($arrayStat),
            $precision,
            count($arrayStat),
            chr(0x0A)
        );
        continue;
    }
    echo sprintf('Target age : %d, no sets%s', $target, chr(0x0A));
}
