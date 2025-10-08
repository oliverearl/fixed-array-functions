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

| Method         | Description                                                                 |                                                       Example |
|:---------------|:----------------------------------------------------------------------------|--------------------------------------------------------------:|
| add            | Alias for push.                                                             |                              `FixedArray::add('bacon', $arr)` |
| addFrom        | Add an array or collection of items to an array.                            |                        `FixedArray::addFrom([1, 2, 3], $arr)` |
| contains       | Checks whether an item exists in a given array.                             |                   `FixedArray::contains('needle', $haystack)` |
| count          | Returns the number of items in a given array.                               |                                   `FixedArray::count($array)` |
| create         | Creates a new fixed array.                                                  |                                      `FixedArray::create(10)` |
| each           | Apply a callback to each item in the array without modifying the original.  |         `FixedArray::each($array, fn () => var_dump($value))` |
| filter         | Applies a filter to a given fixed array.                                    | `FixedArray::filter($array, fn ($value) => $value % 2 === 0)` |
| first          | Returns the first element of the array.                                     |                                   `FixedArray::first($array)` |
| fromArray      | Creates a new fixed array from a standard array.                            |                            `FixedArray::fromArray([1, 2, 3])` |
| fromCollection | Creates a new fixed array from an Illuminate collection.                    |               `FixedArray::fromCollection(collect([1, 2, 3])` |
| getSize        | Alias for count.                                                            |                                 `FixedArray::getSize($array)` |
| isFixedArray   | Returns whether a given item is a fixed array.                              |                   `FixedArray::isFixedArray($potentialArray)` |
| last           | Returns the last element in an array.                                       |                                    `FixedArray::last($array)` |
| map            | Applies a callback to each item in the array and returns it.                |     `FixedArray::map($array, fn ($value) => (string) $value)` |
| merge          | Merges given arrays, fixed arrays, or collections into a given fixed array. |                 `FixedArray::merge($array, $array2, $array3)` |
| nullify        | Overwrite all elements in an array with `null`.                             |                                 `FixedArray::nullify($array)` |
| offsetExists   | Returns whether a given array offset exists.                                |                      `FixedArray::offsetExists(3, $haystack)` |
| offsetGet      | Retrieves the value at a given array offset.                                |                         `FixedArray::offsetGet(3, $haystack)` |
| offsetNull     | Replaces the value at a given array offset with `null`.                     |                        `FixedArray::offsetNull(3, $haystack)` |
| offsetSet      | Replaces the value at a given array offset with a provided value.           |                 `FixedArray::offsetSet(3, $value, $haystack)` |
| pop            | Pops the latest value from the array.                                       |                                     `FixedArray::pop($array)` |
| push           | Pushes a given value to the first available space on the array.             |                            `FixedArray::push($value, $array)` |
| resize         | Alias for setSize.                                                          |                              `FixedArray::resize(10, $array)` |
| second         | Returns the second value from the array.                                    |                                  `FixedArray::second($array)` |
| setSize        | Resizes the array to a given size.                                          |                             `FixedArray::setSize(10, $array)` |
| toArray        | Converts a fixed array into a standard array.                               |                                 `FixedArray::toArray($array)` |
| toCollection   | Converts a fixed array into an Illuminate collection.                       |                            `FixedArray::toCollection($array)` |

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
