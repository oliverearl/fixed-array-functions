# Fixed Array Functions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/petrobolos/fixed-array-functions.svg?style=flat-square)](https://packagist.org/packages/petrobolos/fixed-array-functions)
[![GitHub issues](https://img.shields.io/github/issues/petrobolos/fixed-array-functions)](https://github.com/petrobolos/fixed-array-functions/issues)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/petrobolos/fixed-array-functions/run-tests.yml?label=test&branch=main)](https://github.com/petrobolos/fixed-array-functions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/petrobolos/fixed-array-functions/fix-php-code-style-issues.yml?label=lint&branch=main)](https://github.com/petrobolos/fixed-array-functions/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/petrobolos/fixed-array-functions.svg?style=flat-square)](https://packagist.org/packages/petrobolos/fixed-array-functions)
[![GitHub](https://img.shields.io/github/license/petrobolos/fixed-array-functions)](https://www.github.com/petrobolos/fixed-array-functions)

SplFixedArrays are an implementation of a traditional, bounded array in the PHP standard library.

While they require manual resizing, they're significantly faster than regular arrays or collections when working with
large sets of data.

## Requirements

Currently, requires PHP 8.4 or above, and Laravel 12+.

## Installation

You can install the package via Composer:

```bash
composer require petrobolos/fixed-array-functions
```

## Fluent interface

FixedArray is best used with its fluent interface. If you're familiar with Illuminate collections, you'll feel right at
home. Provide it an SplFixedArray to get started, or a standard array or collection that will be automatically
converted. If you provide any other kind of data, including `null`, it will be inserted into a new SplFixedArray.

```php
use Petrobolos\FixedArray\FixedArrayable;

// You can start by either instantiating a new instance of FixedArrayable, or by calling its helper method:
// The array provided will be converted internally into an SplFixedArray.
$array = new FixedArrayable([1, 2, 3]);

// You can also use the helper function to do the same thing!
$array = fixedArray([1, 2, 3]);

// Lastly, you can use specific methods to begin building your interface logic:
// The same will happen with this collection.
$array = FixedArrayable::fromCollection(collect([1, 2, 3]);

// From here, you can chain different methods, just like you would a collection.
$result = $array
->addFrom([4, 5, 6])
->resize(20)
->filter(fn ($value) => $value % 2 === 0))
->map(fn ($value) => $value * 2))
->get();

// The result will be a SplFixedArray containing [2, 4, 6] but still with 20 indices.
```

## Static methods

You aren't forced to use the fluent interface and can access methods directly by calling them. This is useful if you
only need to do one or two operations on a fixed array.

```php
use Petrobolos\FixedArray;

// Create a fixed array using the create method.
$arr = FixedArray::create();

// Easily push or pop items to and from arrays without worrying about indices.
FixedArray::push('apple', $arr);

// Easily and efficiently merge fixed arrays, regular arrays, and even Illuminate collections.
$everything = FixedArray::merge(
    $arr,
    ['a', 'regular', 'array'],
    collect(['and', 'an', 'illuminate', 'collection']),
);
```

## Full list of working methods

| Method         | Description                                                         |
|:---------------|:--------------------------------------------------------------------|
| add            | Alias for push.                                                     |
| addFrom        | Add an array or collection of items to a fixed array.               |
| chunk          | Split a fixed array into chunks of a given size.                    |
| chunkWhile     | Split a fixed array into chunks while a callback returns true.      |
| contains       | Check whether an item exists in a fixed array.                      |
| create         | Create a new fixed array of a given size.                           |
| dsfargeg       | DSFARGEG                                                            |
| each           | Apply a callback to each item without modifying the original array. |
| fill           | Fill the array with a single value.                                 |
| filter         | Filter the array using a callback.                                  |
| find           | Return the first element matching a callback.                       |
| findKey        | Return the key of the first element matching a callback.            |
| findIndex      | Alias for findKey.                                                  |
| first          | Return the first element of the array.                              |
| fluent         | Creates a new fluent interface for chaining methods.                |
| flatten        | Flatten nested arrays, collections, and fixed arrays.               |
| fromArray      | Create a fixed array from a standard array.                         |
| fromCollection | Create a fixed array from an Illuminate collection.                 |
| getSize        | Return the number of elements in the array.                         |
| isFixedArray   | Check whether a value is a fixed array.                             |
| last           | Return the last element of the array.                               |
| map            | Apply a callback to each item and return a new array.               |
| merge          | Merge multiple arrays, fixed arrays, or collections.                |
| nullify        | Replace all elements with null.                                     |
| offsetExists   | Check if an index exists in the array.                              |
| offsetGet      | Get the value at a specific index.                                  |
| offsetNull     | Set a specific index to null.                                       |
| offsetSet      | Set a value at a specific index.                                    |
| pop            | Remove and return the last element.                                 |
| push           | Add a value to the first available space.                           |
| random         | Return a random element from the array.                             |
| resize         | Alias for setSize.                                                  |
| reverse        | Reverse the order of elements.                                      |
| second         | Return the second element.                                          |
| setSize        | Resize the array to a given size.                                   |
| shift          | Remove and return the first element.                                |
| shuffle        | Shuffle the array in a secure manner.                               |
| slice          | Return a subset of the array.                                       |
| sort           | Sort the array optionally using a callback.                         |
| toArray        | Convert the fixed array into a standard PHP array.                  |
| toCollection   | Convert the fixed array into an Illuminate collection.              |
| unique         | Remove duplicate values from the array.                             |
| unshift        | Prepend one or more values to the array.                            |

## Testing

Tests are run using Pest. You can run the suite like so:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

We welcome pull requests, especially those improving the package's optimisation and speed, and new
features to bring it into parity with Collection.

Please ensure any functionality submitted has adequate test coverage and documentation (at least in English.)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
