<?php

namespace Lc5\TypedCollection;

/**
 * Class AbstractTypedCollection
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
abstract class AbstractTypedCollection extends \ArrayObject
{
    /**
     * @return string
     */
    abstract protected function getType();

    /**
     * @param array|null $elements
     * @param int $flags
     * @param string $iteratorClass
     */
    public function __construct(array $elements = null, $flags = 0, $iteratorClass = 'ArrayIterator')
    {
        if (!is_string($this->getType()) || strlen($this->getType()) === 0) {
            throw new \LogicException(__CLASS__ . '::getType should return not empty string.');
        }

        $elements = (array) $elements;

        foreach ($elements as $element) {
            $this->checkType($element);
        }

        parent::__construct($elements, $flags, $iteratorClass);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->checkType($value);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param mixed $elements
     * @return array
     */
    public function exchangeArray($elements)
    {
        foreach ($elements as $element) {
            $this->checkType($element);
        }

        return parent::exchangeArray($elements);
    }

    /**
     * @param mixed $element
     */
    private function checkType($element)
    {
        $type = $this->getType();

        if (gettype($element) !== $type && !$element instanceof $type) {
            throw new \UnexpectedValueException(
                'Invalid element type: ' . gettype($element) . '. Only ' . $type . ' is allowed.'
            );
        }
    }
}
