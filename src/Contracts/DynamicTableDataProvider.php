<?php

namespace Dennenboom\VerdantUI\Contracts;

interface DynamicTableDataProvider
{
    /**
     * @return array<int, string|array<string, mixed>>
     */
    public function headers(): array;

    /**
     * @return array<int, array<string, mixed>|array<int, mixed>>
     */
    public function rows(): array;
}
