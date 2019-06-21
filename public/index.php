<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cscfa\Abalone\Ann\Ann;

function testInput(...$indexes) {
    $noError = true;
    foreach ($indexes as $index) {
        if (!isset($_POST[$index])) {
            $noError = false;
        }
    }

    return $noError;
}

if (!testInput('sex', 'length', 'diameter', 'height', 'weight')) {
    echo json_encode([]);
    return;
}

$inputData = [
    $_POST['sex'] == 0 ? 1 : 0,
    $_POST['sex'] == 1 ? 1 : 0,
    $_POST['sex'] == 2 ? 1 : 0,
    floatval($_POST['length']),
    floatval($_POST['diameter']),
    floatval($_POST['height']),
    floatval($_POST['weight'])
];

$file = new SplFileInfo(__DIR__ . '/../ann.net');
$ann = new Ann(null, $file);
$output = $ann->run($inputData);

asort($output);
$output = array_reverse($output, true);
$output = array_slice($output, 0, 5, true);

$sum = array_sum($output);
$percent = 100 / $sum;
$output = array_map(function ($element) use ($percent){
    return number_format($element * $percent, 2);
}, $output);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode((object)$output);
