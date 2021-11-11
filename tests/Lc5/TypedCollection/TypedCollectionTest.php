<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

use PHPUnit\Framework\TestCase;

/**
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
final class TypedCollectionTest extends TestCase
{
    public function testGetType(): void
    {
        $typedCollection = new TypedCollection('type');

        $this->assertSame('type', $typedCollection->getType());
    }
}
