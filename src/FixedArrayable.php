<?php

namespace Petrobolos\FixedArray;

use ArrayAccess;
use Illuminate\Support\Collection;
use JsonSerializable;
use SplFixedArray;

/**
 * FixedArrayable class.
 *
 * Provides a fluent interface for working with fixed arrays.
 */
final class FixedArrayable implements JsonSerializable
{
    /**
     * The underlying array value.
     *
     * @var \SplFixedArray
     */
    protected SplFixedArray $value;

    /**
     * Create a new fixed array fluent interface.
     *
     * @param mixed|null $input
     * @return void
     */
    public function __construct(mixed $input = null)
    {
        if ($input instanceof SplFixedArray) {
            $this->value = $input;
        } elseif ($input instanceof Collection) {
            $this->value = FixedArray::fromCollection($input);
        } else {
            $this->value = is_array($input) ? FixedArray::fromArray($input) : FixedArray::fromArray([$input]);
        }
    }

    /**
     * Alias for push.
     *
     * @see \Petrobolos\FixedArray\FixedArrayable::push()
     * @param mixed $input
     * @return $this
     */
    public function add(mixed $input): self
    {
        return $this->push($input);
    }

    /**
     * Adds values from a given array or array-like object into the current fixed array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::addFrom()
     * @param \ArrayAccess|array $input
     * @return $this
     */
    public function addFrom(ArrayAccess|array $input): self
    {
        return $this->from(FixedArray::addFrom($input, $this->value));
    }

    /**
     * Returns whether a given item is contained within the array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::contains()
     * @param mixed $item
     * @param bool $useStrict
     * @return bool
     */
    public function contains(mixed $item, bool $useStrict = false): bool
    {
        return FixedArray::contains($item, $this->value, $useStrict);
    }

    /**
     * Return the number of elements in the fixed array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::count()
     * @return int
     */
    public function count(): int
    {
        return FixedArray::count($this->value);
    }

    /**
     * Create a new fixed array fluent interface.
     *
     * @see \Petrobolos\FixedArray\FixedArray::create()
     * @param int $count
     * @return $this
     */
    public function create(int $count = 5): self
    {
        return $this->from(FixedArray::create($count));
    }

    /**
     * Apply a callback to each item in the array without modifying the original array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::each()
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback): self
    {
        FixedArray::each($this->value, $callback);

        return $this;
    }

    /**
     * Apply a filter to the fixed array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::filter()
     * @param callable $callback
     * @return $this
     */
    public function filter(callable $callback): self
    {
        return $this->from(FixedArray::filter($this->value, $callback));
    }

    /**
     * Returns the first element of the array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::first()
     * @return mixed
     */
    public function first(): mixed
    {
        return FixedArray::first($this->value);
    }

    /**
     * Create a new fixed array fluent interface from a given input.
     *
     * @param mixed $input
     * @return $this
     */
    public function from(mixed $input): self
    {
        return new FixedArrayable($input);
    }

    /**
     * Create a fixed array from a standard array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::fromArray()
     * @param array $array
     * @return $this
     */
    public function fromArray(array $array): self
    {
        return $this->from($array);
    }

    /**
     * Create a fixed array from a collection.
     *
     * @see \Petrobolos\FixedArray\FixedArray::fromCollection()
     * @param \Illuminate\Support\Collection $collection
     * @return $this
     */
    public function fromCollection(Collection $collection): self
    {
        return $this->from($collection);
    }

    /**
     * Retrieve the underlying fixed array value.
     *
     * @return \SplFixedArray
     */
    public function get(): SplFixedArray
    {
        return $this->value;
    }

    /**
     * Return the last element of the array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::last()
     * @return mixed
     */
    public function last(): mixed
    {
        return FixedArray::last($this->value);
    }

    /**
     * Apply a callback to each item in the array and return it.
     *
     * @see \Petrobolos\FixedArray\FixedArray::map()
     * @param string|callable $callback
     * @return $this
     */
    public function map(string|callable $callback): self
    {
        return $this->from(FixedArray::map($this->value, $callback));
    }

    /**
     * Merge the current fixed array with any number of iterable objects or arrays.
     *
     * @see \Petrobolos\FixedArray\FixedArray::merge()
     * @param array|\SplFixedArray|\Illuminate\Support\Collection ...$inputs
     * @return $this
     */
    public function merge(array|SplFixedArray|Collection ...$inputs): self
    {
        return $this->from(FixedArray::merge($this->value, $inputs));
    }

    /**
     * Pop the last element off the fixed array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::pop()
     * @return mixed
     */
    public function pop(): mixed
    {
        return FixedArray::pop($this->value);
    }

    /**
     * Push a value onto the fixed array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::push()
     * @param mixed $input
     * @return $this
     */
    public function push(mixed $input): self
    {
        return $this->from(FixedArray::push($input, $this->value));
    }

    /**
     * Resizes the fixed array to the given number of indices.
     *
     * @see \Petrobolos\FixedArray\FixedArray::resize()
     * @param int $indices
     * @return $this
     */
    public function resize(int $indices): self
    {
        FixedArray::resize($indices, $this->value);

        return $this;
    }

    /**
     * Returns the second value in the fixed array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::second()
     * @return mixed
     */
    public function second(): mixed
    {
        return FixedArray::second($this->value);
    }

    /**
     * Convert the fixed array into a standard PHP array.
     *
     * @see \Petrobolos\FixedArray\FixedArray::toArray()
     * @return array
     */
    public function toArray(): array
    {
        return FixedArray::toArray($this->value);
    }

    /**
     * Convert the fixed array to a collection.
     *
     * @see \Petrobolos\FixedArray\FixedArray::toCollection()
     * @return \Illuminate\Support\Collection
     */
    public function toCollection(): Collection
    {
        return FixedArray::toCollection($this->value);
    }

    /**
     * Get the JSON representation of the arrayable object.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
