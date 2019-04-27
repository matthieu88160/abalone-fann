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

namespace Cscfa\Abalone\Layer;

use Countable;

/**
 * LayerDefinition
 *
 * This class is used to define a layer element.
 *
 * @category Fann
 * @package  Abalone_fann
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class LayerDefinition implements Countable
{
    private $neuronCount;

    private $activationFunction;

    /**
     * LayerDefinition constructor.
     *
     * The LayerDefinition constructor store the number of neuron for the layer.
     *
     * @param int $neuronCount The neuron count in the layer
     *
     * @return void
     */
    public function __construct($neuronCount, $activationFunction = FANN_LINEAR)
    {
        $this->neuronCount = $neuronCount;
        $this->activationFunction = $activationFunction;
    }

    /**
     * Count
     *
     * Count elements of an object
     *
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->getNeuronCount();
    }

    /**
     * Get neuron count
     *
     * Return the count of neuron in the layer.
     *
     * @return int
     */
    public function getNeuronCount()
    {
        return $this->neuronCount;
    }

    /**
     * Set neuron count
     *
     * Allow to set up the neuron count in the layer.
     *
     * @param int $neuronCount The count of neuron in the layer
     *
     * @return LayerDefinition
     */
    protected function setNeuronCount($neuronCount)
    {
        $this->neuronCount = $neuronCount;
        return $this;
    }

    /**
     * Get activation function
     *
     * Return the activation function for the layer
     *
     * @return int
     */
    public function getActivationFunction()
    {
        return $this->activationFunction;
    }

    /**
     * Set activation function
     *
     * Set up the activation function for the layer
     *
     * @param int $activationFunction The activation function
     *
     * @return LayerDefinition
     */
    public function setActivationFunction($activationFunction)
    {
        $this->activationFunction = $activationFunction;
        return $this;
    }
}