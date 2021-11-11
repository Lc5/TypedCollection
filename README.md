# TypedCollection

[![Build Status](https://github.com/Lc5/TypedCollection/workflows/Build/badge.svg)](https://github.com/Lc5/TypedCollection/actions)
[![Latest Stable Version](http://poser.pugx.org/lc5/typed-collection/v)](https://packagist.org/packages/lc5/typed-collection) 
[![Total Downloads](http://poser.pugx.org/lc5/typed-collection/downloads)](https://packagist.org/packages/lc5/typed-collection)
[![PHP Version Require](http://poser.pugx.org/lc5/typed-collection/require/php)](https://packagist.org/packages/lc5/typed-collection)
[![License](http://poser.pugx.org/lc5/typed-collection/license)](https://packagist.org/packages/lc5/typed-collection) 
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://phpstan.org/)

Create strictly typed collections in PHP.

## Installation

```
$ composer require lc5/typed-collection
```

## AbstractTypedCollection:

An abstract class used to create strictly typed collections implemented as a type-checking wrapper around ```ArrayObject```.
The type of elements in a collection is defined by extending ```AbstractTypedCollection``` and implementing abstract
```AbstractTypedCollection::getType``` method. It should return the type as a string, which can be any class name or one
of the internal types in a form recognised by the internal [gettype()](http://php.net/manual/en/function.gettype.php) function
(```"boolean", "integer", "double", "string", "array", "object", "resource", "NULL"```). ```UnexpectedValueException```
will be thrown, when trying to add an element with an invalid type.
        
### Usage:
  
```php
use Lc5\TypedCollection\AbstractTypedCollection;
use Lc5\TypedCollection\Exception\UnexpectedValueException;

class stdClassCollection extends AbstractTypedCollection
{
    public function getType(): string
    {
        return \stdClass::class; //can be any class or internal type
    }
}

$collection = new stdClassCollection([new \stdClass(), new \stdClass()]);
$collection[] = new \stdClass();

try {
    $collection[] = 'invalid';
} catch (UnexpectedValueException $e) {
    echo $e->getMessage(); //Invalid value type: string. Only \stdClass is allowed.
}

try {
    $collection = new stdClassCollection(['invalid', new \stdClass()]);
} catch (UnexpectedValueException $e) {
    echo $e->getMessage(); //Invalid value type: string. Only \stdClass is allowed.
}

```

## TypedCollection:

A strictly typed collection based on ArrayObject. The type of elements in collection is defined using constructor
argument, which can be any class name or one of the internal types in a form recognised by internal
[gettype()](http://php.net/manual/en/function.gettype.php) function (```"boolean", "integer", "double", "string",
"array", "object", "resource", "NULL"```). ```UnexpectedValueException``` will be thrown, when trying to add element
with invalid type.

### Usage:

```php
use Lc5\TypedCollection\TypedArray;
use Lc5\TypedCollection\Exception\UnexpectedValueException;

$values = [new \stdClass(), new \stdClass()];

$typedCollection = new TypedCollection(\stdClass::class, $values);
$typedCollection[] = new \stdClass();

try {
    $typedCollection[] = 'invalid';
} catch (UnexpectedValueException $e) {
    echo $e->getMessage(); //Invalid value type: string. Only \stdClass is allowed.
}

```
The behavior is identical as in ```AbstractTypedCollection```.
