<?php

namespace Dennenboom\VerdantUI\Tables;

final class DynamicTableSort
{
    /**
     * @param array<int, array{key: string, direction: 'asc'|'desc'}>
     */
    public function __construct(
        public readonly array $columns
    ) {}

    public static function fromRequest(): self
    {
        $keys = array_filter(explode(',', (string) request('sort')));
        $dirs = array_filter(explode(',', (string) request('direction')));

        $columns = [];

        foreach ($keys as $i => $key) {
            $columns[$key] = [
                'key' => $key,
                'direction' => ($dirs[$i] ?? 'asc') === 'desc' ? 'desc' : 'asc',
            ];
        }

        return new self(array_values($columns));
    }

    public function isActive(string $key): bool
    {
        return collect($this->columns)->contains(fn ($c) => $c['key'] === $key);
    }

    public function directionFor(string $key): ?string
    {
        return collect($this->columns)
            ->first(fn ($c) => $c['key'] === $key)['direction']
            ?? null;
    }

    public function toggle(string $key): array
    {
        if (! $this->isActive($key)) {
            return [
                ['key' => $key, 'direction' => 'asc'],
            ];
        }

        return collect($this->columns)->map(function ($c) use ($key) {
            if ($c['key'] === $key) {
                $c['direction'] = $c['direction'] === 'asc' ? 'desc' : 'asc';
            }
            return $c;
        })->all();
    }
}
