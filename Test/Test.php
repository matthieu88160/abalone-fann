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

$file = new SplFileInfo(__DIR__ . '/../ann.net');
$ann = new Ann(null, $file);

$dataSet = unserialize(file_get_contents(__DIR__ . '/../serializedData.ser'));

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

    $age = array_search(max($output), $output);
    if ($dataRow[8] == $age) {
        $success++;
    }
}
echo sprintf('Accuracy : %s%%%s', ($success * (100 / count($dataSet))), chr(0x0A));

