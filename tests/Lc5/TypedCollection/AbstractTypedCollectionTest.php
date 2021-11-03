<?php

declare(strict_types=1);

namespace Lc5\TypedCollection;

use PHPUnit\Framework\TestCase;

/**
 * Class TypedCollectionTest
 *
 * @author Łukasz Krzyszczak <lukasz.krzyszczak@gmail.com>
 */
class AbstractTypedCollectionTest extends TestCase
{
    /**
     * @dataProvider validCollectionDataProvider
     * @param string $type
     * @param array $elements
     */
    public function testConstruct($type, array $elements)
    {
        $collection = $this->buildCollection($type, $elements);

        $this->assertSame($elements, (array) $collection);
    }

    /**
     * @dataProvider validDataProvider
     * @param string $type
     * @param mixed $element
     */
    public function testOffsetSet($type, $element)
    {
        $collection = $this->buildCollection($type);
        $collection[] = $element;

        $this->assertSame($element, $collection[0]);
    }

    /**
     * @dataProvider validCollectionDataProvider
     * @param string $type
     * @param array $elements
     */
    public function testExchangeArray($type, array $elements)
    {
        $collection = $this->buildCollection($type);
        $collection->exchangeArray($elements);

        $this->assertSame($elements, (array) $collection);
    }

    /**
     * @dataProvider invalidTypeDataProvider
     * @param string $type
     */
    public function testConstructThrowsLogicException($type)
    {
        $this->expectException(\LogicException::class);
        $this->buildCollection($type);
    }

    /**
     * @dataProvider invalidCollectionDataProvider
     * @param string $type
     * @param array $elements
     */
    public function testConstructThrowsUnexpectedValueException($type, array $elements)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->buildCollection($type, $elements);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param string $type
     * @param mixed $element
     */
    public function testOffsetSetThrowsUnexpectedValueException($type, $element)
    {
        $this->expectException(\UnexpectedValueException::class);
        $collection = $this->buildCollection($type);
        $collection[] = $element;
    }

    /**
     * @dataProvider invalidCollectionDataProvider
     * @param string $type
     * @param array $elements
     */
    public function testExchangeArrayThrowsUnexpectedValueException($type, array $elements)
    {
        $this->expectException(\UnexpectedValueException::class);
        $collection = $this->buildCollection($type);
        $collection->exchangeArray($elements);
    }

    /**
     * @param string $type
     * @param array|null $elements
     * @return AbstractTypedCollection|\PHPUnit_Framework_MockObject_MockObject
     */
    private function buildCollection($type, array $elements = null)
    {
        $collection = $this->getMockBuilder('Lc5\TypedCollection\AbstractTypedCollection')
            ->disableOriginalConstructor()
            ->setMethods(['getType'])
            ->getMockForAbstractClass();

        $collection->expects($this->any())->method('getType')->will($this->returnValue($type));

        $reflectedClass = new \ReflectionClass(AbstractTypedCollection::class);
        $reflectedClass->getConstructor()->invoke($collection, $elements);

        return $collection;
    }

    /**
     * @return array
     */
    public function validCollectionDataProvider()
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
            ['stdClass', [new \stdClass(), new \stdClass()]],
            ['Closure',  [function () {
            }, function () {
            }]]
        ];
    }

    /**
     * @return array
     */
    public function invalidCollectionDataProvider()
    {
        $allTypes = [true, 1, 1.11, 'string', [], new \stdClass(), fopen('php://memory', 'r'), null, function () {
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
            ['stdClass', $allTypes],
            ['Closure',  $allTypes],
        ];
    }

    /**
     * @return array
     */
    public function validDataProvider()
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
            ['stdClass', new \stdClass()],
            ['Closure',  function () {
            }]
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider()
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
            'stdClass' => new \stdClass(),
            'Closure' => function () {
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
            ['boolean', $allTypes['stdClass']],
            ['boolean', $allTypes['Closure']],

            ['integer', $allTypes['boolean']],
            ['integer', $allTypes['double']],
            ['integer', $allTypes['string']],
            ['integer', $allTypes['array']],
            ['integer', $allTypes['object']],
            ['integer', $allTypes['resource']],
            ['integer', $allTypes['NULL']],
            ['integer', $allTypes['stdClass']],
            ['integer', $allTypes['Closure']],

            ['double', $allTypes['boolean']],
            ['double', $allTypes['integer']],
            ['double', $allTypes['string']],
            ['double', $allTypes['array']],
            ['double', $allTypes['object']],
            ['double', $allTypes['resource']],
            ['double', $allTypes['NULL']],
            ['double', $allTypes['stdClass']],
            ['double', $allTypes['Closure']],

            ['string', $allTypes['boolean']],
            ['string', $allTypes['integer']],
            ['string', $allTypes['double']],
            ['string', $allTypes['array']],
            ['string', $allTypes['object']],
            ['string', $allTypes['resource']],
            ['string', $allTypes['NULL']],
            ['string', $allTypes['stdClass']],
            ['string', $allTypes['Closure']],

            ['array', $allTypes['boolean']],
            ['array', $allTypes['integer']],
            ['array', $allTypes['double']],
            ['array', $allTypes['string']],
            ['array', $allTypes['object']],
            ['array', $allTypes['resource']],
            ['array', $allTypes['NULL']],
            ['array', $allTypes['stdClass']],
            ['array', $allTypes['Closure']],

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
            ['resource', $allTypes['stdClass']],
            ['resource', $allTypes['Closure']],

            ['NULL', $allTypes['boolean']],
            ['NULL', $allTypes['integer']],
            ['NULL', $allTypes['double']],
            ['NULL', $allTypes['string']],
            ['NULL', $allTypes['array']],
            ['NULL', $allTypes['object']],
            ['NULL', $allTypes['resource']],
            ['NULL', $allTypes['stdClass']],
            ['NULL', $allTypes['Closure']],

            ['stdClass', $allTypes['boolean']],
            ['stdClass', $allTypes['integer']],
            ['stdClass', $allTypes['double']],
            ['stdClass', $allTypes['string']],
            ['stdClass', $allTypes['array']],
            ['stdClass', $allTypes['resource']],
            ['stdClass', $allTypes['NULL']],
            ['stdClass', $allTypes['Closure']],

            ['Closure', $allTypes['boolean']],
            ['Closure', $allTypes['integer']],
            ['Closure', $allTypes['double']],
            ['Closure', $allTypes['string']],
            ['Closure', $allTypes['array']],
            ['Closure', $allTypes['object']],
            ['Closure', $allTypes['resource']],
            ['Closure', $allTypes['NULL']],
            ['Closure', $allTypes['stdClass']]
        ];
    }

    public function invalidTypeDataProvider()
    {
        return [
            [true],
            [1],
            [1.11],
            [''],
            [[]],
            [new \stdClass()],
            [fopen('php://memory', 'r')],
            [null],
            [function () {
            }]
        ];
    }
}
