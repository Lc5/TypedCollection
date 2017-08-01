# TypedCollection [![Build Status](https://travis-ci.org/Lc5/TypedCollection.svg?branch=master)](https://travis-ci.org/Lc5/TypedCollection)
Create strictly typed collections in PHP.

## Installation

```
$ composer install lc5/typed-collection
```

## AbstractTypedCollection:

An abstract class used to create strictly typed collections implemented as a type-checking wrapper around ```ArrayObject```.
The type of elements in collection is defined by extending ```AbstractTypedCollection``` and implementing abstract
```AbstractTypedCollection::getType``` method. It should return the type as a string, which can be any class name or one
of the internal types in a form recognised by internal [gettype()](http://php.net/manual/en/function.gettype.php) function
(```"boolean", "integer", "double", "string", "array", "object", "resource", "NULL"```). ```\UnexpectedValueException```
will be thrown, when trying to add element with invalid type.
        
### Usage:
  
```php
use Lc5\TypedCollection\AbstractTypedCollection;

class stdClassCollection extends AbstractTypedCollection
{
    public function getType()
    {
        return '\stdClass'; //can be any class or internal type
    }
}

$elements = [new \stdClass(), new \stdClass()];

$collection   = new stdClassCollection($elements);
$collection[] = new \stdClass();

try {
    $collection[] = 'invalid element';
} catch (\UnexpectedValueException $e) {
    echo $e->getMessage(); //Invalid element type: string. Only \stdClass is allowed.
}

try {
    $collection = new stdClassCollection(['invalid', new \stdClass()]);
} catch (\UnexpectedValueException $e) {
    echo $e->getMessage(); //Invalid element type: string. Only \stdClass is allowed.
}

```

## TypedCollection:

A strictly typed collection based on ArrayObject. The type of elements in collection is defined using constructor
argument, which can be any class name or one of the internal types in a form recognised by internal
[gettype()](http://php.net/manual/en/function.gettype.php) function (```"boolean", "integer", "double", "string",
"array", "object", "resource", "NULL"```). ```\UnexpectedValueException``` will be thrown, when trying to add element
with invalid type.

### Usage:

```php
use Lc5\TypedCollection\TypedCollection;

$elements = [new \stdClass(), new \stdClass()];

$collection = new TypedCollection('\stdClass', $elements);

```
The behavior is identical as in ```AbstractTypedCollection```.
