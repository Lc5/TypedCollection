<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

use PHPUnit\Framework\TestCase;

/**
 * Class TypedCollectionTest
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
final class AbstractTypedCollectionTest extends TestCase
{
    /**
     * @dataProvider validCollectionDataProvider
     * @param array<int, mixed> $elements
     */
    public function testConstruct(string $type, array $elements): void
    {
        $collection = $this->buildCollection($type, $elements);

        $this->assertSame($elements, (array) $collection);
    }

    /**
     * @dataProvider validDataProvider
     * @param mixed $element
     */
    public function testOffsetSet(string $type, $element): void
    {
        $collection = $this->buildCollection($type);
        $collection[] = $element;

        $this->assertSame($element, $collection[0]);
    }

    /**
     * @dataProvider validCollectionDataProvider
     * @param array<int, mixed> $elements
     */
    public function testExchangeArray(string $type, array $elements): void
    {
        $collection = $this->buildCollection($type);
        $collection->exchangeArray($elements);

        $this->assertSame($elements, (array) $collection);
    }

    public function testConstructThrowsLogicException(): void
    {
        $this->expectException(\LogicException::class);
        $this->buildCollection('');
    }

    /**
     * @dataProvider invalidCollectionDataProvider
     * @param array<int, mixed> $elements
     */
    public function testConstructThrowsUnexpectedValueException(string $type, array $elements): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->buildCollection($type, $elements);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param mixed $element
     */
    public function testOffsetSetThrowsUnexpectedValueException(string $type, $element): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $collection = $this->buildCollection($type);
        $collection[] = $element;
    }

    /**
     * @dataProvider invalidCollectionDataProvider
     * @param array<int, mixed> $elements
     */
    public function testExchangeArrayThrowsUnexpectedValueException(string $type, array $elements): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $collection = $this->buildCollection($type);
        $collection->exchangeArray($elements);
    }

    private function buildCollection(string $type, array $elements = null): AbstractTypedCollection
    {
        $collection = $this->getMockBuilder(AbstractTypedCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getType'])
            ->getMockForAbstractClass();

        $collection->expects($this->any())->method('getType')->will($this->returnValue($type));

        $reflectedClass = new \ReflectionClass(AbstractTypedCollection::class);
        $reflectedClass->getConstructor()->invoke($collection, $elements);

        return $collection;
    }

    public function validCollectionDataProvider(): array
    {
        return [
            ['boolean',  [true, false]],
            ['integer',  [-1, 0, 1]],
            ['double',   [-1.11, 0.00, 1.11]],
            ['string',   ['first string', 'second string']],
            ['array',    [[], []]],
            ['object',   [new \stdClass(), new \stdClass()]],
            ['resource', [fopen('php://memory', 'r'), fopen('php://memory', 'r')]],
            ['NULL',     [null, null]],
            [\stdClass::class, [new \stdClass(), new \stdClass()]],
            [\Closure::class,  [function (): void {
            }, function (): void {
            }]]
        ];
    }

    public function invalidCollectionDataProvider(): array
    {
        $allTypes = [true, 1, 1.11, 'string', [], new \stdClass(), fopen('php://memory', 'r'), null, function (): void {
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
            [\stdClass::class, $allTypes],
            [\Closure::class,  $allTypes],
        ];
    }

    public function validDataProvider(): array
    {
        return [
            ['boolean',  true],
            ['integer',  1],
            ['double',   1.11],
            ['string',   'string'],
            ['array',    []],
            ['object',   new \stdClass()],
            ['resource', fopen('php://memory', 'r+')],
            ['NULL',     null],
            [\stdClass::class, new \stdClass()],
            [\Closure::class,  function (): void {
            }]
        ];
    }

    public function invalidDataProvider(): array
    {
        $allTypes = [
            'boolean' => true,
            'integer' => 1,
            'double' => 1.11,
            'string' => 'string',
            'array' => [],
            'object' => new \stdClass(),
            'resource' => fopen('php://memory', 'r'),
            'NULL' => null,
            \stdClass::class => new \stdClass(),
            \Closure::class => function (): void {
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
            ['boolean', $allTypes[\stdClass::class]],
            ['boolean', $allTypes[\Closure::class]],

            ['integer', $allTypes['boolean']],
            ['integer', $allTypes['double']],
            ['integer', $allTypes['string']],
            ['integer', $allTypes['array']],
            ['integer', $allTypes['object']],
            ['integer', $allTypes['resource']],
            ['integer', $allTypes['NULL']],
            ['integer', $allTypes[\stdClass::class]],
            ['integer', $allTypes[\Closure::class]],

            ['double', $allTypes['boolean']],
            ['double', $allTypes['integer']],
            ['double', $allTypes['string']],
            ['double', $allTypes['array']],
            ['double', $allTypes['object']],
            ['double', $allTypes['resource']],
            ['double', $allTypes['NULL']],
            ['double', $allTypes[\stdClass::class]],
            ['double', $allTypes[\Closure::class]],

            ['string', $allTypes['boolean']],
            ['string', $allTypes['integer']],
            ['string', $allTypes['double']],
            ['string', $allTypes['array']],
            ['string', $allTypes['object']],
            ['string', $allTypes['resource']],
            ['string', $allTypes['NULL']],
            ['string', $allTypes[\stdClass::class]],
            ['string', $allTypes[\Closure::class]],

            ['array', $allTypes['boolean']],
            ['array', $allTypes['integer']],
            ['array', $allTypes['double']],
            ['array', $allTypes['string']],
            ['array', $allTypes['object']],
            ['array', $allTypes['resource']],
            ['array', $allTypes['NULL']],
            ['array', $allTypes[\stdClass::class]],
            ['array', $allTypes[\Closure::class]],

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
            ['resource', $allTypes[\stdClass::class]],
            ['resource', $allTypes[\Closure::class]],

            ['NULL', $allTypes['boolean']],
            ['NULL', $allTypes['integer']],
            ['NULL', $allTypes['double']],
            ['NULL', $allTypes['string']],
            ['NULL', $allTypes['array']],
            ['NULL', $allTypes['object']],
            ['NULL', $allTypes['resource']],
            ['NULL', $allTypes[\stdClass::class]],
            ['NULL', $allTypes[\Closure::class]],

            [\stdClass::class, $allTypes['boolean']],
            [\stdClass::class, $allTypes['integer']],
            [\stdClass::class, $allTypes['double']],
            [\stdClass::class, $allTypes['string']],
            [\stdClass::class, $allTypes['array']],
            [\stdClass::class, $allTypes['resource']],
            [\stdClass::class, $allTypes['NULL']],
            [\stdClass::class, $allTypes[\Closure::class]],

            [\Closure::class, $allTypes['boolean']],
            [\Closure::class, $allTypes['integer']],
            [\Closure::class, $allTypes['double']],
            [\Closure::class, $allTypes['string']],
            [\Closure::class, $allTypes['array']],
            [\Closure::class, $allTypes['object']],
            [\Closure::class, $allTypes['resource']],
            [\Closure::class, $allTypes['NULL']],
            [\Closure::class, $allTypes[\stdClass::class]]
        ];
    }
}
