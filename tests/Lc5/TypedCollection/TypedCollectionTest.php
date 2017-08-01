<?php

namespace Lc5\TypedCollection;

/**
 * Class TypedCollectionTest
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
class TypedCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetType()
    {
        $collection = new TypedCollection('type');

        $this->assertEquals('type', $collection->getType());
    }
}
