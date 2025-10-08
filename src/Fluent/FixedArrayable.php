<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Fluent;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Petrobolos\FixedArray\FixedArray;
use SplFixedArray;

class FixedArrayable implements Arrayable
{
    /**
     * Create a new fluent interface instance.
     *
     * @param \SplFixedArray<mixed> $data
     */
    private function __construct(private SplFixedArray $data) {}

    /**
     * Create a new fluent interface.
     */
    public static function make(mixed $value, ?int $count = null, bool $preserveKeys = true): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if ($value instanceof FixedArray) {
            return new self(FixedArray::create());
        }

        if ($value instanceof SplFixedArray) {
            return new self($value);
        }

        if ($value instanceof Arrayable) {
            return new self(FixedArray::fromArray($value->toArray(), preserveKeys: $preserveKeys));
        }

        if (is_array($value)) {
            return new self(FixedArray::fromArray($value, preserveKeys: $preserveKeys));
        }

        // If a valid count is provided, create a fixed array of that size and push the value.
        if ($count !== null && $count > 0) {
            $array = FixedArray::create($count);
            FixedArray::offsetSet(0, $value, $array);

            return new self($array);
        }

        // Otherwise, just create a fixed array with the single value in it with a single index.
        return new self(FixedArray::fromArray([$value]));
    }

    /**
     * Alias for make.
     *
     * @see \Petrobolos\FixedArray\Fluent\FixedArrayable::make()
     */
    public static function use(mixed $value, ?int $count = null, bool $preserveKeys = true): self
    {
        return self::make($value, $count, $preserveKeys);
    }

    /**
     * Alias for push.
     *
     * @see \Petrobolos\FixedArray\Fluent\FixedArrayable::push()
     */
    public function add(mixed $value): self
    {
        return $this->push($value);
    }

    /**
     * Add multiple values from an iterable (array, collection, etc.) to the fixed array.
     *
     * @param iterable<mixed> $items
     */
    public function addFrom(iterable $items): self
    {
        $this->data = FixedArray::addFrom($items, $this->data);

        return $this;
    }

    /**
     * Chunk the fixed array into smaller fixed arrays of a given size.
     */
    public function chunk(int $size): self
    {
        $this->data = FixedArray::chunk($this->data, $size);

        return $this;
    }

    /**
     * Chunk the fixed array into smaller fixed arrays while the given callback returns true.
     */
    public function chunkWhile(callable $callback): self
    {
        $this->data = FixedArray::chunkWhile($this->data, $callback);

        return $this;
    }

    /**
     * Determine if the fixed array contains a given value.
     */
    public function contains(mixed $value, bool $useStrict = true): bool
    {
        return FixedArray::contains($value, $this->data, $useStrict);
    }

    /**
     * Use the DSFARGEG algorithm to replace your data with something totally better.
     */
    public function dsfargeg(): SplFixedArray
    {
        $this->data = FixedArray::dsfargeg();

        return $this->data;
    }

    /**
     * Execute a callback for each item in the fixed array.
     */
    public function each(callable $callback): self
    {
        FixedArray::each($this->data, $callback);

        return $this;
    }

    /**
     * Fill the fixed array with a given value.
     */
    public function fill(mixed $value): self
    {
        $this->data = FixedArray::fill($this->data, $value);

        return $this;
    }

    /**
     * Find the first item in the fixed array that matches the given callback.
     */
    public function find(callable $callback): mixed
    {
        return FixedArray::find($this->data, $callback);
    }

    /**
     * Find the key/index of the first item in the fixed array that matches the given callback.
     */
    public function findKey(callable $callback): ?int
    {
        return FixedArray::findKey($this->data, $callback);
    }

    /**
     * Alias for findKey.
     *
     * @see \Petrobolos\FixedArray\Fluent\FixedArrayable::findKey()
     */
    public function findIndex(callable $callback): ?int
    {
        return $this->findKey($callback);
    }

    /**
     * Get the first item in the fixed array.
     */
    public function first(): mixed
    {
        return FixedArray::first($this->data);
    }

    /**
     * Flatten a multi-dimensional fixed array into a single dimension.
     */
    public function flatten(?int $depth = null): self
    {
        $this->data = FixedArray::flatten($this->data, $depth);

        return $this;
    }

    /**
     * Get the underlying SplFixedArray instance.
     *
     * @return \SplFixedArray<mixed>
     */
    public function get(): SplFixedArray
    {
        return $this->data;
    }

    /**
     * Get the size of the fixed array.
     */
    public function getSize(): int
    {
        return FixedArray::getSize($this->data);
    }

    /**
     * Get the last item in the fixed array.
     */
    public function last(): mixed
    {
        return FixedArray::last($this->data);
    }

    /**
     * Map each item in the fixed array through a callback function.
     */
    public function map(callable $callback): self
    {
        $this->data = FixedArray::map($this->data, $callback);

        return $this;
    }

    /**
     * Merge one or more arrays or iterables into the fixed array.
     *
     * @param \SplFixedArray<mixed>|iterable<mixed> ...$sources
     */
    public function merge(SplFixedArray|iterable ...$sources): self
    {
        $this->data = FixedArray::merge($this->data, ...$sources);

        return $this;
    }

    /**
     * Nullify all items in the fixed array.
     */
    public function nullify(): self
    {
        $this->data = FixedArray::nullify($this->data);

        return $this;
    }

    /**
     * Determine if an index exists in the fixed array.
     */
    public function offsetExists(int $index): bool
    {
        return FixedArray::offsetExists($index, $this->data);
    }

    /**
     * Get the value at a given index in the fixed array.
     */
    public function offsetGet(int $index): mixed
    {
        return FixedArray::offsetGet($index, $this->data);
    }

    /**
     * Set the value at a given index in the fixed array.
     */
    public function offsetSet(int $index, mixed $value): self
    {
        FixedArray::offsetSet($index, $value, $this->data);

        return $this;
    }

    /**
     * Pop and return the last item from the fixed array.
     */
    public function pop(): mixed
    {
        return FixedArray::pop($this->data);
    }

    /**
     * Pop the last item from the fixed array and append it to the given output array.
     * Allows method chaining to continue.
     *
     * @param array<int, mixed> $output
     */
    public function popToArray(array &$output): self
    {
        $output[] = FixedArray::pop($this->data);

        return $this;
    }

    /**
     * Push a value onto the end of the fixed array.
     */
    public function push(mixed $value): self
    {
        $this->data = FixedArray::push($value, $this->data);

        return $this;
    }

    /**
     * Get a random item from the fixed array.
     *
     * @throws \Random\RandomException
     */
    public function random(): mixed
    {
        return FixedArray::random($this->data);
    }

    /**
     * Alias for setSize.
     *
     * @see \Petrobolos\FixedArray\Fluent\FixedArrayable::setSize()
     */
    public function resize(int $size): self
    {
        return $this->setSize($size);
    }

    /**
     * Reverse the order of items in the fixed array.
     */
    public function reverse(): self
    {
        $this->data = FixedArray::reverse($this->data);

        return $this;
    }

    /**
     * Get the second item in the fixed array.
     */
    public function second(): mixed
    {
        return FixedArray::second($this->data);
    }

    /**
     * Set the size of the fixed array.
     */
    public function setSize(int $size): self
    {
        FixedArray::setSize($size, $this->data);

        return $this;
    }

    /**
     * Shift and return the first item from the fixed array.
     */
    public function shift(): mixed
    {
        return FixedArray::shift($this->data);
    }

    /**
     * Shuffle the items in the fixed array randomly.
     */
    public function shuffle(): self
    {
        $this->data = FixedArray::shuffle($this->data);

        return $this;
    }

    /**
     * Slice a portion of the fixed array.
     */
    public function slice(int $offset, ?int $length = null): self
    {
        $this->data = FixedArray::slice($this->data, $offset, $length);

        return $this;
    }

    /**
     * Sort the fixed array using an optional callback for comparison.
     */
    public function sort(?callable $callback = null): self
    {
        $this->data = FixedArray::sort($this->data, $callback);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return FixedArray::toArray($this->data);
    }

    /**
     * Get the instance as an Illuminate collection.
     *
     * @return \Illuminate\Support\Collection<int, mixed>
     */
    public function toCollection(): Collection
    {
        return FixedArray::toCollection($this->data);
    }

    /**
     * Alias for get.
     *
     * @see \Petrobolos\FixedArray\Fluent\FixedArrayable::get()
     *
     * @return \SplFixedArray<mixed>
     */
    public function toFixedArray(): SplFixedArray
    {
        return $this->get();
    }

    /**
     * Remove duplicate values from the fixed array.
     */
    public function unique(bool $useStrict = true): self
    {
        $this->data = FixedArray::unique($this->data, $useStrict);

        return $this;
    }

    /**
     * Unshift a value onto the beginning of the fixed array.
     */
    public function unshift(mixed $value): self
    {
        $this->data = FixedArray::unshift($value, $this->data);

        return $this;
    }
}
