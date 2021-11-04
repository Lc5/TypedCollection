<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

use PHPUnit\Framework\TestCase;

/**
 * Class TypedCollectionTest
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
final class TypedCollectionTest extends TestCase
{
    public function testGetType()
    {
        $typedCollection = new TypedCollection('type');

        $this->assertEquals('type', $typedCollection->getType());
    }
}
