<?php

namespace Dennenboom\VerdantUI\Tables;

final class Column
{
    private string $key;

    private string $label;

    private ?\Closure $valueCallback = null;

    private ?\Closure $formatCallback = null;

    private ?\Closure $renderCallback = null;

    private bool $sortable = false;

    private ?string $sortKey = null;

    private ?\Closure $sortValueCallback = null;

    private ?\Closure $sortQueryCallback = null;

    private ?\Closure $visibleWhenCallback = null;

    private bool $isDefault = false;

    private bool $pinned = false;

    private string $class = '';

    private ?string $width = null;

    /** @var 'left'|'center'|'right'|null */
    private ?string $align = null;

    private const ALIGN_VALUES = ['left', 'center', 'right'];

    private ?string $tooltip = null;

    private function __construct(string $key, string $label)
    {
        $this->key = $key;
        $this->label = $label;
    }

    public static function make(string $key, string $label): self
    {
        return new self($key, $label);
    }

    /**
     * Mark as a default column (included in default visible columns when using column visibility).
     */
    public function default(): self
    {
        $this->isDefault = true;

        return $this;
    }

    /**
     * Set a computed value callback. Use for columns not directly mapped to a model attribute.
     *
     * @param  callable(mixed): mixed  $callback  Receives the model, returns the value
     */
    public function value(callable $callback): self
    {
        $this->valueCallback = \Closure::fromCallable($callback);

        return $this;
    }

    /**
     * Set a format callback to transform the raw value.
     *
     * @param  callable(mixed, mixed): (string|int|float|bool|array{value?: mixed, html?: bool, class?: string})  $callback  Receives (value, model), returns scalar or array
     */
    public function format(callable $callback): self
    {
        $this->formatCallback = \Closure::fromCallable($callback);

        return $this;
    }

    /**
     * Set a custom render callback. Fully app-controlled UI (Htmlable / View).
     *
     * @param  callable(mixed, mixed): \Illuminate\Contracts\Support\Htmlable  $callback  Receives (value, model), returns Htmlable
     */
    public function render(callable $callback): self
    {
        $this->renderCallback = \Closure::fromCallable($callback);

        return $this;
    }

    /**
     * Make the column sortable.
     *
     * @param  bool|string|callable  $key  true = sort by column key; string = DB column name for ORDER BY; callable = in-memory sort value (fn ($model) => mixed)
     */
    public function sortable(bool|string|callable $key = true): self
    {
        $this->sortable = true;

        if (is_string($key)) {
            $this->sortKey = $key;
        } elseif (is_callable($key)) {
            $this->sortValueCallback = \Closure::fromCallable($key);
        }

        return $this;
    }

    /**
     * Set the value to use for in-memory sorting (e.g. when not using DB pagination).
     *
     * @param  callable(mixed): mixed  $callback  Receives the model, returns the value to compare
     */
    public function sortValue(callable $callback): self
    {
        $this->sortValueCallback = \Closure::fromCallable($callback);

        return $this;
    }

    /**
     * Set a callback to apply complex DB sort (joins, raw expressions).
     *
     * @param  callable(Builder, string): void  $callback  Receives (query, direction 'asc'|'desc')
     */
    public function sortableQuery(callable $callback): self
    {
        $this->sortQueryCallback = \Closure::fromCallable($callback);

        return $this;
    }

    /**
     * Show this column only when the callback returns true (table-level visibility).
     *
     * @param  callable(mixed): bool  $callback  Receives context (e.g. first model or auth); return false to hide column
     */
    public function visibleWhen(callable $callback): self
    {
        $this->visibleWhenCallback = \Closure::fromCallable($callback);

        return $this;
    }

    /**
     * Pin the column (always visible, cannot be hidden in column picker).
     */
    public function pinned(bool $pinned = true): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * Set optional CSS class for the header cell.
     */
    public function class(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Set column width hint (e.g. '8rem', 'minmax(100px, 1fr)').
     */
    public function width(string $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set text alignment for header and cells ('left', 'center', 'right').
     *
     * @param  'left'|'center'|'right'  $align
     */
    public function align(string $align): self
    {
        $align = strtolower($align);
        if (! in_array($align, self::ALIGN_VALUES, true)) {
            throw new \InvalidArgumentException(
                'Column align must be one of: ' . implode(', ', self::ALIGN_VALUES) . ', got: ' . $align
            );
        }
        $this->align = $align;

        return $this;
    }

    /**
     * Set header tooltip (title attribute).
     */
    public function tooltip(string $tooltip): self
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Convert to the definition array expected by DynamicTableData.
     *
     * @return array<string, mixed>
     */
    public function toDefinition(): array
    {
        $definition = [
            'label' => $this->label,
            'sortable' => $this->sortable,
        ];

        if ($this->isDefault) {
            $definition['default'] = true;
        }

        if ($this->class !== '') {
            $definition['class'] = $this->class;
        }

        if ($this->sortKey !== null) {
            $definition['sort_key'] = $this->sortKey;
        }

        if ($this->sortValueCallback !== null) {
            $definition['sort_value'] = $this->sortValueCallback;
        }

        if ($this->sortQueryCallback !== null) {
            $definition['sort_query'] = $this->sortQueryCallback;
        }

        if ($this->visibleWhenCallback !== null) {
            $definition['visible_when'] = $this->visibleWhenCallback;
        }

        if ($this->pinned) {
            $definition['pinned'] = true;
        }

        if ($this->width !== null) {
            $definition['width'] = $this->width;
        }

        if ($this->align !== null) {
            $definition['align'] = $this->align;
        }

        if ($this->tooltip !== null) {
            $definition['tooltip'] = $this->tooltip;
        }

        if ($this->valueCallback !== null) {
            $definition['value'] = $this->valueCallback;
        }

        if ($this->formatCallback !== null) {
            $definition['format'] = $this->formatCallback;
        }

        if ($this->renderCallback !== null) {
            $definition['render'] = $this->renderCallback;
        }

        return $definition;
    }

    /**
     * Resolve the actual DB column to use for sorting when the user sorts by a column key.
     *
     * Use when a column is computed (e.g. full_name) but you want to sort by a real DB column (e.g. last_name).
     *
     * @param  string  $requestedKey  The column key from the sort request (e.g. full_name)
     * @param  iterable<Column|array<string, mixed>>  $columns  Column objects or key => definition arrays
     * @return string  The DB column name to use for ORDER BY
     */
    public static function resolveSortKey(string $requestedKey, iterable $columns): string
    {
        foreach ($columns as $keyOrIndex => $columnOrDefinition) {
            $key = $columnOrDefinition instanceof self
                ? $columnOrDefinition->getKey()
                : (is_string($keyOrIndex) ? $keyOrIndex : null);

            if ($key === null || $key !== $requestedKey) {
                continue;
            }

            $definition = $columnOrDefinition instanceof self
                ? $columnOrDefinition->toDefinition()
                : (is_array($columnOrDefinition) ? $columnOrDefinition : []);

            if (isset($definition['sort_key']) && is_string($definition['sort_key'])) {
                return $definition['sort_key'];
            }

            return $requestedKey;
        }

        return $requestedKey;
    }

    /**
     * Apply sort for one column to the query (server-side). Uses sort_query callback when present, otherwise orderBy with resolveSortKey.
     *
     * @param  string  $requestedKey  Column key from the sort request
     * @param  iterable<Column|array<string, mixed>>  $columns  Column objects or key => definition arrays
     * @param  \Illuminate\Contracts\Database\Query\Builder  $query
     * @param  'asc'|'desc'  $direction
     */
    public static function applySort(string $requestedKey, iterable $columns, $query, string $direction): void
    {
        $definition = null;

        foreach ($columns as $keyOrIndex => $columnOrDefinition) {
            $key = $columnOrDefinition instanceof self
                ? $columnOrDefinition->getKey()
                : (is_string($keyOrIndex) ? $keyOrIndex : null);

            if ($key === null || $key !== $requestedKey) {
                continue;
            }

            $definition = $columnOrDefinition instanceof self
                ? $columnOrDefinition->toDefinition()
                : (is_array($columnOrDefinition) ? $columnOrDefinition : []);

            break;
        }

        if ($definition !== null && isset($definition['sort_query']) && is_callable($definition['sort_query'])) {
            ($definition['sort_query'])($query, $direction);

            return;
        }

        $sortKey = self::resolveSortKey($requestedKey, $columns);
        $query->orderBy($sortKey, $direction);
    }
}
