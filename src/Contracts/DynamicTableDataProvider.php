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

    /**
     * Column keys to search when the user submits the search bar.
     * Return null to hide the search bar.
     *
     * @return array<string>|null
     */
    public function searchableColumns(): ?array;

    /**
     * URL for API search. When set, the search bar fetches from this URL (with ?q=...)
     * and shows results in a dropdown; selecting an item navigates to its url.
     * API should return JSON array of objects with at least "url" and "label".
     * Return null to use form-submit search instead.
     *
     * @return string|null
     */
    public function searchApiUrl(): ?string;

    /**
     * Filter definitions for the filter modal. Each item: key, label, type (text|number|date|checkbox|select),
     * and for select: options (array of ['value' => x, 'label' => y] or value => label).
     * Return null to hide the filter button/modal.
     *
     * @return array<int, array<string, mixed>>|null
     */
    public function filterColumns(): ?array;
}
