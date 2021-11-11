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

use ArrayIterator;

/**
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
