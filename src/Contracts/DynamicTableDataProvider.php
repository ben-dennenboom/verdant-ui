<?php

namespace Dennenboom\VerdantUI\Contracts;

use Dennenboom\VerdantUI\Tables\DynamicTableSort;
use Illuminate\Pagination\AbstractPaginator;

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

    /**
     * @return AbstractPaginator|null
     */
    public function paginator(): ?AbstractPaginator;

    /**
     * @return DynamicTableSort|null
     */
    public function sort(): ?DynamicTableSort;
}
