# AGENTS.md

## Overview

Laravel package providing Collection-like helper methods for PHP's `SplFixedArray` - a high-performance, fixed-size array implementation. The package offers both static methods (`FixedArray`) and a fluent chainable interface (`FixedArrayable`).

**Current Version**: 3.1.0 (70+ methods with full Collection parity)

## Architecture

```
src/
├── FixedArray.php           # Core static methods (primary logic)
├── helpers.php              # Global `fixedArray()` helper function
├── Fluent/
│   └── FixedArrayable.php   # Fluent wrapper delegating to FixedArray
├── Facades/
│   └── FixedArray.php       # Laravel facade
└── Providers/
    └── FixedArrayServiceProvider.php  # Spatie package-tools based
```

**Pattern**: `FixedArrayable` (fluent) wraps and delegates to `FixedArray` (static). When adding new methods, implement in `FixedArray.php` first, then add fluent wrapper in `FixedArrayable.php`.

## Developer Commands

```bash
composer test              # Run Pest tests (290+ tests)
composer test:coverage     # Run with 100% coverage requirement
composer test:type-coverage # PHPStan type coverage
composer analyse           # PHPStan level 9 analysis
composer format            # Laravel Pint (PER preset)
```

## Code Conventions

- **Strict types**: All files use `declare(strict_types=1);`
- **PHPDoc**: Document `@param \SplFixedArray<mixed>` for all array parameters
- **Return types**: Methods return `SplFixedArray` for chaining, mutate in-place when possible
- **Static methods**: First parameter is typically `SplFixedArray $array`
- **Performance**: Avoid array conversions (`toArray()`) when possible - iterate directly over `SplFixedArray`
- **Aliasing**: Common aliases (e.g., `add` → `push`, `resize` → `setSize`) follow this pattern:
  ```php
  public static function add(SplFixedArray $fixedArray, mixed $value): SplFixedArray
  {
      return self::push($fixedArray, $value);
  }
  ```

## Testing Patterns

Tests use Pest with `describe()` blocks per method. Example structure:

```php
describe('method name', function (): void {
    it('describes behavior', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::methodName($array, ...);
        expect($result)->toBeInstanceOf(SplFixedArray::class);
    });
    
    it('handles empty array', function (): void {
        // Edge case testing
    });
});
```

Architecture tests enforce: strict types, documented methods/properties, strict equality, Laravel preset compliance.

## Key Implementation Details

- **Array conversion**: Use `FixedArray::fromArray()` / `FixedArray::toArray()` for PHP array interop
- **Size management**: `push()` auto-extends array size; manual resize via `setSize()` / `resize()`
- **Null handling**: Empty slots contain `null`; `nullify()` clears all values
- **Collection interop**: `fromCollection()` / `toCollection()` for Laravel Collections
- **Fluent entry points**: `FixedArrayable::make()`, `fixedArray()` helper, or `FixedArray::fluent()`
- **Callback signatures**: Most callbacks receive `(mixed $value, int $key)` to match Collection behavior
- **Property access**: Methods like `sum()`, `avg()`, `min()`, `max()`, `pluck()` support property names as strings

## Method Categories

- **Creation**: `create`, `fromArray`, `fromCollection`, `fluent`
- **Aggregation**: `sum`, `avg`, `min`, `max`, `reduce`, `count`
- **Filtering**: `filter`, `reject`, `partition`, `unique`
- **Mapping**: `map`, `each`, `flatten`, `pluck`
- **Searching**: `find`, `findKey`, `contains`, `first`, `last`
- **Conditionals**: `when`, `unless`, `every`, `all`, `some`, `isEmpty`, `isNotEmpty`
- **Utilities**: `tap`, `pipe`, `join`, `keys`, `values`
- **Sorting**: `sort`, `reverse`, `shuffle`
- **Chunking**: `chunk`, `chunkWhile`, `slice`
- **Modification**: `push`, `pop`, `shift`, `unshift`, `fill`, `merge`

## Requirements

- PHP 8.4+
- Laravel 12+ (illuminate/contracts)


