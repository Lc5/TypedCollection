<?php

declare(strict_types=1);
/*
 * This file is part of the lc5/typed-collection package.
 *
 * (c) Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Lc5\TypedCollection;

use PHPUnit\Framework\TestCase;

final class TypedCollectionTest extends TestCase
{
    public function testGetType(): void
    {
        $typedCollection = new TypedCollection('type');

        $this->assertSame('type', $typedCollection->getType());
    }
}
