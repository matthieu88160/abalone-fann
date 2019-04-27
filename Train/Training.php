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
use Cscfa\Abalone\Layer\LayerDefinition;
use Cscfa\Abalone\Layer\LayerHeap;

require_once __DIR__ . '/../vendor/autoload.php';

// COS : 1.5
// SIGMOID : 13.5
// LINEAR : 19.1
// SIN : 0.5
// ELLIOT : 17.7
// GAUSSIAN : 13.5
// LINEAR_PIECE : 13.5

$activationType = FANN_LINEAR;
$heap = new LayerHeap(
    new LayerDefinition(7),
    [
        new LayerDefinition(9, $activationType),
        new LayerDefinition(12, $activationType),
        new LayerDefinition(16, $activationType),
        new LayerDefinition(21, $activationType),
        new LayerDefinition(28, $activationType),
    ],
    new LayerDefinition(29, $activationType)
);

$ann = new Ann($heap);

$ann->trainFromFile(
    new SplFileInfo(__DIR__ . '/../trainingFile.data'),
    2000,
    0,
    0.01
);

$ann->save(__DIR__ . '/../ann.net');
