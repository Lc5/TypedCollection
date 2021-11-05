<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

/**
 * Class TypedCollection
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 *
 * @template T
 * @extends AbstractTypedCollection<T>
 */
final class TypedCollection extends AbstractTypedCollection
{
    private string $type;

    /**
     * @param array<T>|null $elements
     */
    public function __construct(
        string $type,
        array $elements = null,
        int $flags = 0,
        string $iteratorClass = \ArrayIterator::class
    ) {
        $this->type = $type;

        parent::__construct($elements, $flags, $iteratorClass);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
