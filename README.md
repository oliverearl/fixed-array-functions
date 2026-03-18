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

## Quick Start

### Fluent interface

FixedArray is best used with its fluent interface. If you're familiar with Illuminate collections, you'll feel right at
home. Provide it an SplFixedArray to get started, or a standard array or collection that will be automatically
converted.

```php
use Petrobolos\FixedArray\FixedArrayable;

// Create using various methods
$array = new FixedArrayable([1, 2, 3]);
$array = fixedArray([1, 2, 3]);  // Helper function
$array = FixedArrayable::fromCollection(collect([1, 2, 3]));

// Chain methods just like Laravel Collections
$result = fixedArray([1, 2, 3, 4, 5, 6])
    ->filter(fn ($value) => $value % 2 === 0)
    ->map(fn ($value) => $value * 2)
    ->sum();  // Returns 24 (2*2 + 4*2 + 6*2)
```

### Static methods

You aren't forced to use the fluent interface and can access methods directly by calling them. This is useful if you
only need to do one or two operations on a fixed array.

```php
use Petrobolos\FixedArray\FixedArray;

$arr = FixedArray::fromArray([1, 2, 3, 4, 5]);

// Aggregate functions
$sum = FixedArray::sum($arr);           // 15
$avg = FixedArray::avg($arr);           // 3
$min = FixedArray::min($arr);           // 1
$max = FixedArray::max($arr);           // 5

// Higher-order functions
$doubled = FixedArray::map($arr, fn($v) => $v * 2);
$evens = FixedArray::filter($arr, fn($v) => $v % 2 === 0);

// Conditional operations
$result = FixedArray::when(
    $arr,
    fn($a) => FixedArray::count($a) > 3,
    fn($a) => FixedArray::push($a, 6)
);
```

## Real-World Examples

### Processing Large Datasets

```php
// Efficiently process 100,000 records
$data = FixedArray::create(100000);

for ($i = 0; $i < 100000; $i++) {
    FixedArray::offsetSet($data, $i, fetchRecordFromDatabase($i));
}

$processed = fixedArray($data)
    ->filter(fn($record) => $record->isActive)
    ->map(fn($record) => $record->transform())
    ->toCollection(); // Convert back to Collection for further Laravel operations
```

### Data Aggregation

```php
$orders = fixedArray($orderArray)
    ->pluck('total')
    ->sum();

$averageAge = fixedArray($users)
    ->avg('age');

$oldestUser = fixedArray($users)
    ->max('created_at');
```

### Conditional Transformations

```php
$processed = fixedArray($items)
    ->when(
        $shouldFilter,
        fn($arr) => $arr->filter(fn($item) => $item->isValid())
    )
    ->unless(
        $skipSorting,
        fn($arr) => $arr->sort()
    )
    ->tap(fn($arr) => logger()->info('Processed items', ['count' => $arr->count()]))
    ->toArray();
```

### Partitioning Data

```php
[$valid, $invalid] = fixedArray($records)
    ->partition(fn($record) => $record->validate())
    ->toArray();

// $valid contains all validated records
// $invalid contains all failed records
```

## Performance Benefits

`SplFixedArray` offers significant performance advantages over standard PHP arrays for large datasets:

- **Memory Efficiency**: Uses approximately 50% less memory than standard arrays
- **Faster Access**: Direct memory access without hash table overhead
- **Predictable Performance**: O(1) access time for all operations

**When to use SplFixedArray:**
- Working with large datasets (10,000+ elements)
- Memory-constrained environments
- Performance-critical array operations
- Known array size at creation time

**When to stick with regular arrays/Collections:**
- Small datasets (< 1,000 elements)
- Dynamic sizing with frequent additions/removals
- Need associative keys or complex key types

## Full list of available methods

| Method         | Description                                                         |
|:---------------|:--------------------------------------------------------------------|
| add            | Alias for push.                                                     |
| addFrom        | Add an array or collection of items to a fixed array.               |
| all            | Alias for every.                                                    |
| avg            | Calculate the average of numeric values.                            |
| average        | Alias for avg.                                                      |
| chunk          | Split a fixed array into chunks of a given size.                    |
| chunkWhile     | Split a fixed array into chunks while a callback returns true.      |
| contains       | Check whether an item exists in a fixed array.                      |
| count          | Return the number of elements.                                      |
| create         | Create a new fixed array of a given size.                           |
| dsfargeg       | DSFARGEG                                                            |
| each           | Apply a callback to each item without modifying the original array. |
| every          | Determine if all items pass a given test.                           |
| fill           | Fill the array with a single value.                                 |
| filter         | Filter the array using a callback (passes key to callback).         |
| find           | Return the first element matching a callback.                       |
| findKey        | Return the key of the first element matching a callback.            |
| findIndex      | Alias for findKey.                                                  |
| first          | Return the first element of the array.                              |
| flatten        | Flatten nested arrays, collections, and fixed arrays.               |
| fluent         | Creates a new fluent interface for chaining methods.                |
| fromArray      | Create a fixed array from a standard array.                         |
| fromCollection | Create a fixed array from an Illuminate collection.                 |
| getSize        | Return the number of elements in the array.                         |
| implode        | Alias for join.                                                     |
| isEmpty        | Check if the array is empty.                                        |
| isFixedArray   | Check whether a value is a fixed array.                             |
| isNotEmpty     | Check if the array is not empty.                                    |
| join           | Join array elements with a string.                                  |
| keys           | Get all keys (indices) from the array.                              |
| last           | Return the last element of the array.                               |
| map            | Apply a callback to each item and return a new array.               |
| max            | Find the maximum value.                                             |
| merge          | Merge multiple arrays, fixed arrays, or collections.                |
| min            | Find the minimum value.                                             |
| nullify        | Replace all elements with null.                                     |
| offsetExists   | Check if an index exists in the array.                              |
| offsetGet      | Get the value at a specific index.                                  |
| offsetNull     | Set a specific index to null.                                       |
| offsetSet      | Set a value at a specific index.                                    |
| partition      | Split array into two groups based on a callback.                    |
| pipe           | Pass the array through a callback and return the result.            |
| pluck          | Extract values from a specific property or key.                     |
| pop            | Remove and return the last element.                                 |
| push           | Add a value to the first available space.                           |
| random         | Return a random element from the array.                             |
| reduce         | Reduce the array to a single value using a callback.                |
| reject         | Filter out items that pass the test (opposite of filter).           |
| resize         | Alias for setSize.                                                  |
| reverse        | Reverse the order of elements.                                      |
| second         | Return the second element.                                          |
| setSize        | Resize the array to a given size.                                   |
| shift          | Remove and return the first element.                                |
| shuffle        | Shuffle the array in a secure manner.                               |
| slice          | Return a subset of the array.                                       |
| some           | Determine if at least one item passes a given test.                 |
| sort           | Sort the array optionally using a callback.                         |
| sum            | Sum all numeric values in the array.                                |
| tap            | Pass the array to a callback and return the array.                  |
| toArray        | Convert the fixed array into a standard PHP array.                  |
| toCollection   | Convert the fixed array into an Illuminate collection.              |
| unique         | Remove duplicate values from the array.                             |
| unshift        | Prepend one or more values to the array.                            |
| unless         | Apply callback if the condition is false.                           |
| values         | Get all values from the array (reindexed).                          |
| when           | Apply callback if the condition is true.                            |

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
