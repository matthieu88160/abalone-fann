<?php
/**
 * This file is part of the abalone_fann project.
 *
 * As each files provides by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 5.6
 *
 * @category Fann
 * @package  Abalone_fann
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */

namespace Cscfa\Abalone\Ann;

use function count;
use Cscfa\Abalone\Layer\LayerHeap;
use function iterator_to_array;
use RuntimeException;

/**
 * Ann
 *
 * .The artificial neural network wrapper
 *
 * @category Fann
 * @package  Abalone_fann
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class Ann
{
    /**
     * Ann
     *
     * The artificial neural network
     *
     * @var resource
     */
    private $ann;

    /**
     * Ann constructor.
     *
     * Initialize the neural network
     *
     * @param LayerHeap $layers The layer definition heap
     *
     * @return void
     */
    public function __construct(LayerHeap $layers)
    {
        $this->createNeuralNetwork($layers);
    }

    /**
     * Create neural network
     *
     * Create a neural network based on the given layer definition
     *
     * @param LayerHeap $layers The layer definition heap
     *
     * @return void
     * @throws RuntimeException
     */
    private function createNeuralNetwork(LayerHeap $layers)
    {
        $ann = fann_create_standard_array(count($layers), iterator_to_array($layers));

        if (!$ann) {
            throw new RuntimeException('Unable to create artificial neuron network');
        }

        $this->ann = $ann;
    }
}