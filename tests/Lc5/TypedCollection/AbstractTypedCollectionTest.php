<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

use ArrayIterator;
use Closure;
use LogicException;
use PHPUnit\Framework\TestCase;
use stdClass;
use UnexpectedValueException;

/**
 * Class TypedCollectionTest
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
final class AbstractTypedCollectionTest extends TestCase
{
    /**
     * @dataProvider validCollectionDataProvider
     * @param array<int, mixed> $array
     */
    public function testConstruct(string $type, array $array): void
    {
        $collection = $this->buildCollection($type, $array);

        $this->assertSame($array, (array) $collection);
    }

    /**
     * @dataProvider validDataProvider
     * @param mixed $value
     */
    public function testOffsetSet(string $type, $value): void
    {
        $collection = $this->buildCollection($type);
        $collection[] = $value;

        $this->assertSame($value, $collection[0]);
    }

    /**
     * @dataProvider validDataProvider
     * @param mixed $value
     */
    public function testAppend(string $type, $value): void
    {
        $collection = $this->buildCollection($type);
        $collection->append($value);

        $this->assertSame($value, $collection[0]);
    }

    /**
     * @dataProvider validCollectionDataProvider
     * @param array<int, mixed> $array
     */
    public function testExchangeArray(string $type, array $array): void
    {
        $collection = $this->buildCollection($type);
        $collection->exchangeArray($array);

        $this->assertSame($array, (array) $collection);
    }

    public function testConstructThrowsLogicException(): void
    {
        $this->expectException(LogicException::class);
        $this->buildCollection('');
    }

    /**
     * @dataProvider invalidCollectionDataProvider
     * @param array<int, mixed> $array
     */
    public function testConstructThrowsUnexpectedValueException(string $type, array $array): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->buildCollection($type, $array);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param mixed $value
     */
    public function testOffsetSetThrowsUnexpectedValueException(string $type, $value): void
    {
        $this->expectException(UnexpectedValueException::class);
        $collection = $this->buildCollection($type);
        $collection[] = $value;
    }

    /**
     * @dataProvider invalidDataProvider
     * @param mixed $value
     */
    public function testAppendThrowsUnexpectedValueException(string $type, $value): void
    {
        $this->expectException(UnexpectedValueException::class);
        $collection = $this->buildCollection($type);
        $collection->append($value);
    }

    /**
     * @dataProvider invalidCollectionDataProvider
     * @param array<int, mixed> $array
     */
    public function testExchangeArrayThrowsUnexpectedValueException(string $type, array $array): void
    {
        $this->expectException(UnexpectedValueException::class);
        $collection = $this->buildCollection($type);
        $collection->exchangeArray($array);
    }

    public function testGetIteratorReturnsArrayCopy(): void
    {
        $collection = $this->buildCollection('integer', [1, 2]);

        $iterator = $collection->getIterator();
        $iterator[] = 3;

        $this->assertSame([1, 2], $collection->getArrayCopy());
        $this->assertSame([1, 2, 3], $iterator->getArrayCopy());
    }

    /**
     * @param array<mixed>|null $array
     * @return AbstractTypedCollection<mixed>
     */
    private function buildCollection(string $type, array $array = null): AbstractTypedCollection
    {
        return new class($type, $array) extends AbstractTypedCollection {
            private string $type;

            /**
             * @param array<mixed> $array
             */
            public function __construct(string $type, array $array = null)
            {
                $this->type = $type;
                parent::__construct($array);
            }

            protected function getType(): string
            {
                return $this->type;
            }
        };
    }

    /**
     * @return array<mixed>
     */
    public function validCollectionDataProvider(): array
    {
        return [
            ['boolean',  [true, false]],
            ['integer',  [-1, 0, 1]],
            ['double',   [-1.11, 0.00, 1.11]],
            ['string',   ['first string', 'second string']],
            ['array',    [[], []]],
            ['object',   [new stdClass(), new stdClass()]],
            ['resource', [fopen('php://memory', 'r'), fopen('php://memory', 'r')]],
            ['NULL',     [null, null]],
            ['iterable', [[], new ArrayIterator()]],
            [stdClass::class, [new stdClass(), new stdClass()]],
            [Closure::class,  [function (): void {
            }, function (): void {
            }]]
        ];
    }

    /**
     * @return array<mixed>
     */
    public function invalidCollectionDataProvider(): array
    {
        $allTypes = [true, 1, 1.11, 'string', [], new stdClass(), fopen('php://memory', 'r'), null, function (): void {
        }];

        return [
            ['boolean',  $allTypes],
            ['integer',  $allTypes],
            ['double',   $allTypes],
            ['string',   $allTypes],
            ['array',    $allTypes],
            ['object',   $allTypes],
            ['resource', $allTypes],
            ['NULL',     $allTypes],
            ['iterable', $allTypes],
            [stdClass::class, $allTypes],
            [Closure::class,  $allTypes],
        ];
    }

    /**
     * @return array<mixed>
     */
    public function validDataProvider(): array
    {
        return [
            ['boolean',  true],
            ['integer',  1],
            ['double',   1.11],
            ['string',   'string'],
            ['array',    []],
            ['object',   new stdClass()],
            ['resource', fopen('php://memory', 'r+')],
            ['NULL',     null],
            ['iterable', []],
            [stdClass::class, new stdClass()],
            [Closure::class,  function (): void {
            }]
        ];
    }

    /**
     * @return array<mixed>
     */
    public function invalidDataProvider(): array
    {
        $allTypes = [
            'boolean' => true,
            'integer' => 1,
            'double' => 1.11,
            'string' => 'string',
            'array' => [],
            'object' => new stdClass(),
            'resource' => fopen('php://memory', 'r'),
            'NULL' => null,
            'iterable' => new ArrayIterator(),
            stdClass::class => new stdClass(),
            Closure::class => function (): void {
            }
        ];
        
        return [
            ['boolean', $allTypes['integer']],
            ['boolean', $allTypes['double']],
            ['boolean', $allTypes['string']],
            ['boolean', $allTypes['array']],
            ['boolean', $allTypes['object']],
            ['boolean', $allTypes['resource']],
            ['boolean', $allTypes['NULL']],
            ['boolean', $allTypes['iterable']],
            ['boolean', $allTypes[stdClass::class]],
            ['boolean', $allTypes[Closure::class]],

            ['integer', $allTypes['boolean']],
            ['integer', $allTypes['double']],
            ['integer', $allTypes['string']],
            ['integer', $allTypes['array']],
            ['integer', $allTypes['object']],
            ['integer', $allTypes['resource']],
            ['integer', $allTypes['NULL']],
            ['integer', $allTypes['iterable']],
            ['integer', $allTypes[stdClass::class]],
            ['integer', $allTypes[Closure::class]],

            ['double', $allTypes['boolean']],
            ['double', $allTypes['integer']],
            ['double', $allTypes['string']],
            ['double', $allTypes['array']],
            ['double', $allTypes['object']],
            ['double', $allTypes['resource']],
            ['double', $allTypes['NULL']],
            ['double', $allTypes['iterable']],
            ['double', $allTypes[stdClass::class]],
            ['double', $allTypes[Closure::class]],

            ['string', $allTypes['boolean']],
            ['string', $allTypes['integer']],
            ['string', $allTypes['double']],
            ['string', $allTypes['array']],
            ['string', $allTypes['object']],
            ['string', $allTypes['resource']],
            ['string', $allTypes['NULL']],
            ['string', $allTypes['iterable']],
            ['string', $allTypes[stdClass::class]],
            ['string', $allTypes[Closure::class]],

            ['array', $allTypes['boolean']],
            ['array', $allTypes['integer']],
            ['array', $allTypes['double']],
            ['array', $allTypes['string']],
            ['array', $allTypes['object']],
            ['array', $allTypes['resource']],
            ['array', $allTypes['NULL']],
            ['array', $allTypes['iterable']],
            ['array', $allTypes[stdClass::class]],
            ['array', $allTypes[Closure::class]],

            ['object', $allTypes['boolean']],
            ['object', $allTypes['integer']],
            ['object', $allTypes['double']],
            ['object', $allTypes['string']],
            ['object', $allTypes['array']],
            ['object', $allTypes['resource']],
            ['object', $allTypes['NULL']],

            ['resource', $allTypes['boolean']],
            ['resource', $allTypes['integer']],
            ['resource', $allTypes['double']],
            ['resource', $allTypes['string']],
            ['resource', $allTypes['array']],
            ['resource', $allTypes['object']],
            ['resource', $allTypes['NULL']],
            ['resource', $allTypes['iterable']],
            ['resource', $allTypes[stdClass::class]],
            ['resource', $allTypes[Closure::class]],

            ['NULL', $allTypes['boolean']],
            ['NULL', $allTypes['integer']],
            ['NULL', $allTypes['double']],
            ['NULL', $allTypes['string']],
            ['NULL', $allTypes['array']],
            ['NULL', $allTypes['object']],
            ['NULL', $allTypes['resource']],
            ['NULL', $allTypes['iterable']],
            ['NULL', $allTypes[stdClass::class]],
            ['NULL', $allTypes[Closure::class]],

            [stdClass::class, $allTypes['boolean']],
            [stdClass::class, $allTypes['integer']],
            [stdClass::class, $allTypes['double']],
            [stdClass::class, $allTypes['string']],
            [stdClass::class, $allTypes['array']],
            [stdClass::class, $allTypes['resource']],
            [stdClass::class, $allTypes['NULL']],
            [stdClass::class, $allTypes['iterable']],
            [stdClass::class, $allTypes[Closure::class]],

            [Closure::class, $allTypes['boolean']],
            [Closure::class, $allTypes['integer']],
            [Closure::class, $allTypes['double']],
            [Closure::class, $allTypes['string']],
            [Closure::class, $allTypes['array']],
            [Closure::class, $allTypes['object']],
            [Closure::class, $allTypes['resource']],
            [Closure::class, $allTypes['NULL']],
            [Closure::class, $allTypes['iterable']],
            [Closure::class, $allTypes[stdClass::class]]
        ];
    }
}
