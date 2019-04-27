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

use Cscfa\Abalone\Layer\LayerDefinition;
use Cscfa\Abalone\Layer\LayerHeap;
use RuntimeException;
use function count;
use function iterator_to_array;
use SplFileInfo;

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
     * @param null $fileName
     *
     * @return void
     */
    public function __construct(LayerHeap $layers = null, SplFileInfo $fileName = null)
    {
        if ($fileName) {
            $ann = fann_create_from_file($fileName->getPathname());

            if (!$ann) {
                throw new RuntimeException('Unable to create artificial neuron network');
            }

            $this->ann = $ann;
            return;
        }

        if (!$layers) {
            throw new RuntimeException('Layer heap must be provided');
        }

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
        $ann = fann_create_standard_array(
            count($layers),
            array_map('count', iterator_to_array($layers))
        );

        if (!$ann) {
            throw new RuntimeException('Unable to create artificial neuron network');
        }

        $this->ann = $ann;
        $this->setActivationLayers($layers);
    }

    private function setActivationLayers(LayerHeap $layers)
    {
        /** @var LayerDefinition $layer */
        foreach ($layers as $layerPosition => $layer) {
            if ($layerPosition == 0) {
                continue;
            }

            fann_set_activation_function_layer(
                $this->ann,
                $layer->getActivationFunction(),
                $layerPosition
            );
        }
    }

    /**
     * Train from file
     *
     * Train the neural network from the data contained in a file
     *
     * @param SplFileInfo $file The file containing the data
     * @param int $maxEpochs The max epoch count for the training
     * @param int $epochBetweenReports The epoch count between reports
     * @param float $desiredErrors The squared desired error count
     *
     * @return void
     */
    public function trainFromFile(SplFileInfo $file, $maxEpochs, $epochBetweenReports, $desiredErrors)
    {
        $result = fann_train_on_file(
            $this->ann,
            $file->getPathname(),
            $maxEpochs,
            $epochBetweenReports,
            $desiredErrors
        );

        if (!$result) {
            throw new RuntimeException('Training fail');
        }
    }

    /**
     * Save
     *
     * Save the neural network to file
     *
     * @param string $fileName The file name where save the neural network
     *
     * @return void
     */
    public function save($fileName)
    {
        $result = fann_save($this->ann, $fileName);

        if (!$result) {
            throw new RuntimeException('Fail to save the artificial neural network');
        }
    }

    /**
     * Run
     *
     * Run input through the neural network
     *
     * @param array $input The array of input value
     *
     * @return array|false
     */
    public function run(array $input)
    {
        return fann_run($this->ann, $input);
    }

    /**
     * Set epoch callback
     *
     * Set the epoch callback function
     *
     * @param Callable $callback The callback to inject
     *
     * @return void
     */
    public function setEpochCallback(Callable $callback)
    {
        $result = fann_set_callback($this->ann, $callback);

        if (!$result) {
            throw new RuntimeException('Fail to save the artificial neural network');
        }
    }

    /**
     * PHP 5 introduces a destructor concept similar to that of other object-oriented languages, such as C++.
     * The destructor method will be called as soon as all references to a particular object are removed or
     * when the object is explicitly destroyed or in any order in shutdown sequence.
     *
     * Like constructors, parent destructors will not be called implicitly by the engine.
     * In order to run a parent destructor, one would have to explicitly call parent::__destruct() in the destructor
     * body.
     *
     * Note: Destructors called during the script shutdown have HTTP headers already sent.
     * The working directory in the script shutdown phase can be different with some SAPIs (e.g. Apache).
     *
     * Note: Attempting to throw an exception from a destructor (called in the time of script termination) causes a
     * fatal error.
     *
     * @return void
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __destruct()
    {
        fann_destroy($this->ann);
    }
}