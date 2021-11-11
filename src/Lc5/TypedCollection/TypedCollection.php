<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

use ArrayIterator;

/**
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 *
 * @template T
 * @extends AbstractTypedCollection<T>
 */
final class TypedCollection extends AbstractTypedCollection
{
    private string $type;

    /**
     * @param array<T>|null $array
     */
    public function __construct(
        string $type,
        array $array = null,
        int $flags = 0,
        string $iteratorClass = ArrayIterator::class
    ) {
        $this->type = $type;

        parent::__construct($array, $flags, $iteratorClass);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
