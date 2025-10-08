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
     * Get the underlying SplFixedArray instance.
     *
     * @return \SplFixedArray<mixed>
     */
    public function get(): SplFixedArray
    {
        return $this->data;
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
}
