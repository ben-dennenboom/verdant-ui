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

    /**
     * Storage key for column visibility preferences (e.g. for localStorage).
     * Return null if this table does not support column visibility.
     *
     * @return string|null
     */
    public function columnVisibilityKey(): ?string;

    /**
     * Default visible column keys when no preference is stored.
     * Return null to mean "all visible".
     *
     * @return array<string>|null
     */
    public function defaultVisibleColumns(): ?array;
}
