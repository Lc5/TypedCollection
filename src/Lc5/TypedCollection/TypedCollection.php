<?php

namespace Lc5\TypedCollection;

/**
 * Class TypedCollection
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
class TypedCollection extends AbstractTypedCollection
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     * @param array|null $elements
     * @param int $flags
     * @param string $iteratorClass
     */
    public function __construct($type, array $elements = null, $flags = 0, $iteratorClass = 'ArrayIterator')
    {
        $this->type = $type;

        parent::__construct($elements, $flags, $iteratorClass);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
