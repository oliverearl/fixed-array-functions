# Contributing to Fixed Array Functions

We welcome contributions to make this package even better! Whether you're fixing bugs, adding features, improving documentation, or helping with tests, your contribution is valued.

## Code of Conduct

This project adheres to a Code of Conduct. By participating, you are expected to uphold this code. Please report unacceptable behavior to [oliver.earl@petrobolos.com](mailto:oliver.earl@petrobolos.com).

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When creating a bug report, include:

- A clear and descriptive title
- Steps to reproduce the issue
- Expected behavior vs actual behavior
- PHP version, Laravel version, and package version
- Any relevant code samples or error messages

### Suggesting Features

Feature suggestions are welcome! Please:

- Check if the feature already exists or is planned
- Explain the use case and why it would be valuable
- Consider if it fits the package's goal of providing Collection-like helpers for SplFixedArray

### Pull Requests

1. **Fork the repository** and create a feature branch from `main`
2. **Follow the coding standards** (see below)
3. **Write tests** for new functionality - we maintain 100% code coverage
4. **Update documentation** if you're adding/changing public APIs
5. **Run the test suite** and ensure all checks pass

```bash
composer test              # Run tests
composer test:coverage     # Verify 100% coverage
composer analyse           # PHPStan analysis
composer format            # Format code with Pint
```

## Development Setup

```bash
git clone https://github.com/petrobolos/fixed-array-functions.git
cd fixed-array-functions
composer install
composer test
```

## Coding Standards

- **Strict types**: All PHP files must use `declare(strict_types=1);`
- **PHPDoc**: Document all methods and properties with proper type hints
- **Style**: We use Laravel Pint with the PER preset - run `composer format`
- **Testing**: Use Pest with `describe()` blocks for organization
- **Architecture**: 
  - Add methods to `src/FixedArray.php` first (static methods)
  - Then add fluent wrappers in `src/Fluent/FixedArrayable.php`
  - Avoid array conversions when possible for performance

### Method Naming Conventions

- Use Laravel Collection-inspired naming where applicable
- Provide aliases for common alternatives (e.g., `add` → `push`, `average` → `avg`)
- Use `@see` tags to link aliases to their primary implementations

## Testing Guidelines

Tests should:

- Cover all code paths (100% coverage requirement)
- Test edge cases (empty arrays, null values, boundary conditions)
- Use descriptive test names: `it('does something specific')`
- Group related tests with `describe()` blocks

Example:

```php
describe('method name', function (): void {
    it('handles normal case', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::methodName($array);
        expect($result)->toBeInstanceOf(SplFixedArray::class);
    });
    
    it('handles empty array', function (): void {
        // ...
    });
});
```

## Release Process

Maintainers will handle releases following semantic versioning:

- **Patch** (x.x.1): Bug fixes, documentation
- **Minor** (x.1.0): New features (backward compatible)
- **Major** (1.0.0): Breaking changes

## Questions?

Feel free to open an issue for questions or reach out to the maintainers directly.

Thank you for contributing! 🎉

