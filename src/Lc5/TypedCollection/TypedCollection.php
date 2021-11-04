<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

/**
 * Class TypedCollection
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
final class TypedCollection extends AbstractTypedCollection
{
    private string $type;

    /**
     * @param string $type
     * @param array|null $elements
     * @param int $flags
     * @param string $iteratorClass
     */
    public function __construct($type, array $elements = null, $flags = 0, $iteratorClass = \ArrayIterator::class)
    {
        $this->type = $type;

        parent::__construct($elements, $flags, $iteratorClass);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
