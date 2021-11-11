<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

/**
 * Class AbstractTypedCollection
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 *
 * @template T
 * @extends \ArrayObject<int, T>
 */
abstract class AbstractTypedCollection extends \ArrayObject
{
    abstract protected function getType(): string;

    /**
     * @param array<T> $elements
     * @param class-string $iteratorClass
     */
    public function __construct(array $elements = null, int $flags = 0, string $iteratorClass = \ArrayIterator::class)
    {
        if ($this->getType() === '') {
            throw new \LogicException(__CLASS__ . '::getType should return not empty string.');
        }

        $elements = (array) $elements;

        foreach ($elements as $element) {
            $this->checkType($element);
        }

        parent::__construct($elements, $flags, $iteratorClass);
    }

    /**
     * @param int|string|null $offset
     * @param T $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->checkType($value);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param array<T>|object $elements
     * @return array<T>
     */
    public function exchangeArray($elements): array
    {
        foreach ($elements as $element) { /** @phpstan-ignore-line */
            $this->checkType($element);
        }

        return parent::exchangeArray($elements);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->getArrayCopy());
    }

    /**
     * @param mixed $element
     */
    protected function checkType($element): void
    {
        $type = $this->getType();

        if (gettype($element) !== $type &&
            !$element instanceof $type &&
            !($type === 'iterable' && is_iterable($element))) {
            throw new \UnexpectedValueException(
                'Invalid element type: ' . gettype($element) . '. Only ' . $type . ' is allowed.'
            );
        }
    }
}
