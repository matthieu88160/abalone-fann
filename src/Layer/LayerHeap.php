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
use Iterator;
use function count;

/**
 * LayerHeap
 *
 * This class is used to store a heap of layer, both input, output and hidden one.
 *
 * @category Fann
 * @package  Abalone_fann
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class LayerHeap implements Countable, Iterator
{
    /**
     * Layers
     *
     * Store the layers of the heap
     *
     * @var LayerDefinition[]
     */
    private $layers;

    /**
     * Cursor
     *
     * The internal iteration cursor
     *
     * @var int
     */
    private $cursor = 0;

    /**
     * LayerHeap constructor.
     *
     * Put the given layers in the internal store.
     *
     * @param LayerDefinition $inputLayer The input layer
     * @param array $hiddenLayers The list of hidden layers
     * @param LayerDefinition $outputLayer The output layer
     *
     * @return void
     */
    public function __construct(LayerDefinition $inputLayer, array $hiddenLayers, LayerDefinition $outputLayer)
    {
        $this->layers = array_merge(
            [$inputLayer],
            $hiddenLayers,
            [$outputLayer]
        );
    }

    /**
     * Get layer
     *
     * Return the set of layer stored internally
     *
     * @return LayerDefinition[]
     */
    public function getLayers()
    {
        return $this->layers;
    }

    /**
     * Set layers
     *
     * Set up the internal layer store
     *
     * @param LayerDefinition[] $layers The set of internal layer
     *
     * @return LayerHeap
     */
    protected function setLayers($layers)
    {
        $this->layers = $layers;
        return $this;
    }

    /**
     * Count
     *
     * Return the count of layers
     *
     * @return int
     */
    public function count()
    {
        return count($this->layers);
    }

    /**
     * Current
     *
     * Return the current element
     *
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->layers[$this->cursor];
    }

    /**
     * Next
     *
     * Move forward to next element
     *
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->cursor++;
    }

    /**
     * Key
     *
     * Return the key of the current element
     *
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * Valid
     *
     * Checks if current position is valid
     *
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->layers[$this->cursor]);
    }

    /**
     * Rewind
     *
     * Rewind the Iterator to the first element
     *
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->cursor = 0;
    }

    /**
     * Get cursor
     *
     * Return the internal iteration cursor
     *
     * @return int
     */
    protected function getCursor()
    {
        return $this->cursor;
    }

    /**
     * Set cursor
     *
     * Set up the internal iteration cursor
     *
     * @param int $cursor The internal cursor value
     *
     * @return LayerHeap
     */
    protected function setCursor($cursor)
    {
        $this->cursor = $cursor;
        return $this;
    }
}