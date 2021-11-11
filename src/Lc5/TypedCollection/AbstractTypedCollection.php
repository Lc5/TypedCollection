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
     * @param array<T> $array
     * @param class-string $iteratorClass
     */
    public function __construct(array $array = null, int $flags = 0, string $iteratorClass = \ArrayIterator::class)
    {
        if ($this->getType() === '') {
            throw new \LogicException(__CLASS__ . '::getType should return not empty string.');
        }

        $array = (array) $array;

        foreach ($array as $value) {
            $this->checkType($value);
        }

        parent::__construct($array, $flags, $iteratorClass);
    }

    /**
     * @param int|string|null $key
     * @param T $value
     */
    public function offsetSet($key, $value): void
    {
        $this->checkType($value);

        parent::offsetSet($key, $value);
    }

    /**
     * @param array<T>|object $array
     * @return array<T>
     */
    public function exchangeArray($array): array
    {
        foreach ($array as $value) { /** @phpstan-ignore-line */
            $this->checkType($value);
        }

        return parent::exchangeArray($array);
    }

    public function getIterator(): \ArrayIterator
    {
        $iteratorClass = $this->getIteratorClass();

        return new $iteratorClass($this->getArrayCopy()); /** @phpstan-ignore-line */
    }

    /**
     * @param mixed $value
     */
    protected function checkType($value): void
    {
        $type = $this->getType();

        if (gettype($value) !== $type &&
            !$value instanceof $type &&
            !($type === 'iterable' && is_iterable($value))) {
            throw new \UnexpectedValueException(
                'Invalid element type: ' . gettype($value) . '. Only ' . $type . ' is allowed.'
            );
        }
    }
}
