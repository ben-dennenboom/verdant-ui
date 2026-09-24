<?php

namespace Dennenboom\VerdantUI\Contracts;

interface DynamicTablePreferencesStore
{
    /**
     * @return array<string, mixed>|null
     */
    public function get(string $key): ?array;

    /**
     * @param array<string, mixed> $preferences
     */
    public function put(string $key, array $preferences): void;
}
