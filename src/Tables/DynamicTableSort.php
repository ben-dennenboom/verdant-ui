<?php

namespace Dennenboom\VerdantUI\Tables;

final class DynamicTableSort
{
    public function __construct(
        public readonly ?string $key,
        public readonly string $direction = 'asc'
    ) {}

    public static function fromRequest(): self
    {
        $key = request()->query('sort');
        $direction = request()->query('direction', 'asc');

        return new self(
            $key,
            $direction === 'desc' ? 'desc' : 'asc'
        );
    }

    public function toggleDirection(): string
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }
}
