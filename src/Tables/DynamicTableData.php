<?php

namespace Dennenboom\VerdantUI\Tables;

use Dennenboom\VerdantUI\Contracts\DynamicTableDataProvider;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Route;

class DynamicTableData implements DynamicTableDataProvider
{
    /**
     * @var array<int, string|array<string, mixed>>
     */
    protected array $headers;

    /**
     * @var array<int, array<string, mixed>|array<int, mixed>>
     */
    protected array $rows;

    /**
     * @var AbstractPaginator|null
     */
    protected ?AbstractPaginator $paginator = null;

    /**
     * @var DynamicTableSort|null
     */
    protected ?DynamicTableSort $sort = null;

    /**
     * @var string|null
     */
    protected ?string $columnVisibilityKey = null;

    /**
     * @var array<string>|null
     */
    protected ?array $defaultVisibleColumns = null;

    /**
     * @var array<string>|null
     */
    protected ?array $searchableColumns = null;

    /**
     * @var string|null
     */
    protected ?string $searchApiUrl = null;

    /**
     * @var array<int, array<string, mixed>>|null
     */
    protected ?array $filters = null;

    /**
     * Normalized column definitions (key => definition) when built via fromCollection. Used for default visible and export.
     *
     * @var array<string, string|array<string, mixed>>|null
     */
    protected ?array $columnDefinitions = null;

    /**
     * @param array<int, string|array<string, mixed>> $headers
     * @param array<int, array<string, mixed>|array<int, mixed>> $rows
     */
    public function __construct(array $headers, array $rows)
    {
        $this->headers = $headers;
        $this->rows = $rows;
    }

    /**
     * @param array<int, string|array<string, mixed>> $headers
     * @param array<int, array<string, mixed>|array<int, mixed>> $rows
     */
    public static function from(array $headers, array $rows): self
    {
        $rows = self::cacheRouteExistence($rows);

        $instance = new self($headers, $rows);
        $instance->paginator = null;

        return $instance;
    }

    /**
     * @param  iterable<mixed>  $items
     * @param  array<int|string, Column|string|array<string, mixed>>  $columns
     * @param  mixed  $context  Optional context for visible_when (e.g. first model or auth); columns with visible_when returning false are excluded
     */
    public static function fromCollection(
        iterable $items,
        array $columns,
        ?callable $actions = null,
        mixed $context = null
    ): self {
        if (empty($columns)) {
            return new self([], []);
        }

        $columns = self::normalizeColumns($columns);

        if ($context !== null) {
            $columns = self::filterColumnsByVisibleWhen($columns, $context);
        }

        $instance = new self([], []);
        $instance->columnDefinitions = $columns;

        if ($items instanceof AbstractPaginator) {
            $instance->paginator = $items;
            $collection = $items->getCollection();
        } else {
            $collection = collect($items);
        }

        $headerKeys = ['sortable' => true, 'class' => true, 'sort_key' => true, 'pinned' => true, 'width' => true, 'align' => true, 'tooltip' => true];
        $headers = [];
        foreach ($columns as $key => $definition) {
            $headers[] = is_array($definition) ?
                array_merge(
                    [
                        'key'   => $key,
                        'label' => $definition['label'],
                    ],
                    array_intersect_key($definition, $headerKeys)
                ) : [
                    'key'   => $key,
                    'label' => $definition,
                ];
        }

        $rows = $collection->map(function ($model) use ($columns, $actions) {
            $row = [];

            foreach ($columns as $key => $definition) {
                $value = isset($definition['value']) && is_callable($definition['value'])
                    ? ($definition['value'])($model)
                    : data_get($model, $key);

                if (is_array($definition)) {
                    if (isset($definition['format'])) {
                        $value = ($definition['format'])($value, $model);
                    }

                    if (isset($definition['render'])) {
                        $value = ($definition['render'])($value, $model);
                    }
                }

                $row[$key] = $value;

                if (is_array($definition)) {
                    $sortKey = '_sort_' . $key;
                    if (isset($definition['sort_value']) && is_callable($definition['sort_value'])) {
                        $row[$sortKey] = ($definition['sort_value'])($model);
                    } elseif (isset($definition['sort_key']) && is_string($definition['sort_key'])) {
                        $row[$sortKey] = data_get($model, $definition['sort_key']);
                    } else {
                        $row[$sortKey] = $row[$key];
                    }
                }
            }

            if ($actions) {
                $actionsValue = $actions($model);

                if (is_array($actionsValue)) {
                    $render = null;

                    if (isset($actionsValue['render']) && is_callable($actionsValue['render'])) {
                        $render = $actionsValue['render'];
                        unset($actionsValue['render']);
                    }

                    $row['actions'] = array_values($actionsValue);

                    if ($render) {
                        $row['actions_render'] = $render($row, $model);
                    }
                } else {
                    $row['actions'] = $actionsValue;
                }
            }

            return $row;
        })
            ->values()
            ->all();

        $rows = self::cacheRouteExistence($rows);

        $instance->headers = $headers;
        $instance->rows = $rows;

        return $instance;
    }

    /**
     * @return array<int, string|array<string, mixed>>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @return array<int, array<string, mixed>|array<int, mixed>>
     */
    public function rows(): array
    {
        return $this->rows;
    }

    /**
     * @return AbstractPaginator|null
     */
    public function paginator(): ?AbstractPaginator
    {
        return $this->paginator;
    }

    public function sort(): ?DynamicTableSort
    {
        return $this->sort;
    }

    public function columnVisibilityKey(): ?string
    {
        return $this->columnVisibilityKey;
    }

    public function defaultVisibleColumns(): ?array
    {
        return $this->defaultVisibleColumns;
    }

    public function searchableColumns(): ?array
    {
        return $this->searchableColumns;
    }

    public function searchApiUrl(): ?string
    {
        return $this->searchApiUrl;
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    public function filterColumns(): ?array
    {
        return $this->filters;
    }

    /**
     * @param array<Filter> $filters
     */
    public function withFilters(array $filters): self
    {
        $this->filters = [];
        foreach ($filters as $filter) {
            if (! $filter instanceof Filter) {
                throw new \InvalidArgumentException(
                    'Each filter must be a ' . Filter::class . ' instance. Got: ' . (is_object($filter) ? $filter::class : gettype($filter))
                );
            }
            $this->filters[] = $filter->toDefinition();
        }

        return $this;
    }

    /**
     * @param  array<string>|null  $defaultVisible  When null and table was built from Column objects, defaults are derived from columns marked ->default()
     */
    public function withColumnVisibility(string $key, ?array $defaultVisible = null): self
    {
        $this->columnVisibilityKey = $key;

        if ($defaultVisible === null && $this->columnDefinitions !== null) {
            $defaultVisible = [];
            foreach ($this->columnDefinitions as $colKey => $def) {
                if (is_array($def) && ($def['default'] ?? false)) {
                    $defaultVisible[] = $colKey;
                }
            }
        }

        $this->defaultVisibleColumns = $defaultVisible;

        return $this;
    }

    /**
     * @param  array<string>  $columns
     */
    public function withSearchableColumns(array $columns): self
    {
        $this->searchableColumns = $columns;

        return $this;
    }

    public function withSearchApiUrl(string $url): self
    {
        $this->searchApiUrl = $url;

        return $this;
    }

    public function withSorting(DynamicTableSort $sort): self
    {
        $this->sort = $sort;

        if ($this->paginator) {
            return $this;
        }

        if (count($sort->columns)) {
            usort($this->rows, function ($a, $b) use ($sort) {
                foreach ($sort->columns as $col) {
                    $sortKey = '_sort_' . $col['key'];
                    $av = array_key_exists($sortKey, $a) ? $a[$sortKey] : data_get($a, $col['key']);
                    $bv = array_key_exists($sortKey, $b) ? $b[$sortKey] : data_get($b, $col['key']);

                    if ($av === $bv) {
                        continue;
                    }

                    if (is_null($av)) return 1;
                    if (is_null($bv)) return -1;

                    $cmp = $av <=> $bv;

                    if ($cmp !== 0) {
                        return $col['direction'] === 'asc' ? $cmp : -$cmp;
                    }
                }

                return 0;
            });
        }

        return $this;
    }

    /**
     * Build export-ready rows from a collection using the same column definitions (value + format, no render).
     *
     * @param  iterable<mixed>  $items
     * @param  array<int|string, Column|string|array<string, mixed>>  $columns
     * @param  bool  $withHeaderRow  When true, the first row is column_key => label for use as CSV/Excel header
     * @return array<int, array<string, mixed>>  List of associative rows; if $withHeaderRow, first element is the header row
     */
    public static function exportFromCollection(iterable $items, array $columns, bool $withHeaderRow = false): array
    {
        if (empty($columns)) {
            return $withHeaderRow ? [[]] : [];
        }

        $columns = self::normalizeColumns($columns);
        $rows = [];

        if ($withHeaderRow) {
            $headerRow = [];
            foreach ($columns as $key => $definition) {
                $headerRow[$key] = is_array($definition) ? ($definition['label'] ?? $key) : (string) $definition;
            }
            $rows[] = $headerRow;
        }

        foreach ($items as $model) {
            $row = [];
            foreach ($columns as $key => $definition) {
                $value = isset($definition['value']) && is_callable($definition['value'])
                    ? ($definition['value'])($model)
                    : data_get($model, $key);

                if (is_array($definition) && isset($definition['format']) && is_callable($definition['format'])) {
                    $value = ($definition['format'])($value, $model);
                }

                if (is_array($value) && isset($value['value'])) {
                    $value = $value['html'] ?? false ? strip_tags((string) $value['value']) : $value['value'];
                }

                $row[$key] = $value;
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Normalize column definitions. Converts list of Column objects to associative array format.
     *
     * @param  array<int|string, Column|string|array<string, mixed>>  $columns
     * @return array<string, string|array<string, mixed>>
     */
    private static function normalizeColumns(array $columns): array
    {
        if (self::isColumnObjectArray($columns)) {
            $normalized = [];
            foreach ($columns as $column) {
                if ($column instanceof Column) {
                    $normalized[$column->getKey()] = $column->toDefinition();
                }
            }

            return $normalized;
        }

        return $columns;
    }

    /**
     * @param  array<int|string, mixed>  $columns
     */
    private static function isColumnObjectArray(array $columns): bool
    {
        $values = array_values($columns);
        $first = $values[0] ?? null;

        return $first instanceof Column;
    }

    /**
     * Filter out columns whose visible_when callback returns false for the given context.
     *
     * @param  array<string, string|array<string, mixed>>  $columns
     * @return array<string, string|array<string, mixed>>
     */
    private static function filterColumnsByVisibleWhen(array $columns, mixed $context): array
    {
        $filtered = [];

        foreach ($columns as $key => $definition) {
            if (! is_array($definition) || ! isset($definition['visible_when']) || ! is_callable($definition['visible_when'])) {
                $filtered[$key] = $definition;
                continue;
            }
            if (($definition['visible_when'])($context)) {
                $filtered[$key] = $definition;
            }
        }

        return $filtered;
    }

    /**
     * Pre-check route existence for all actions to avoid per-row Route::has() calls.
     *
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    private static function cacheRouteExistence(array $rows): array
    {
        // Collect all unique route names from action definitions
        $routeNames = [];
        foreach ($rows as $row) {
            if (isset($row['actions']) && is_array($row['actions'])) {
                foreach ($row['actions'] as $action) {
                    if (is_array($action) && isset($action['route']) && is_string($action['route'])) {
                        $routeNames[$action['route']] = true;
                    }
                }
            }
        }

        // Pre-check route existence for all unique routes
        $routeExistsCache = [];
        foreach (array_keys($routeNames) as $routeName) {
            $routeExistsCache[$routeName] = Route::has($routeName);
        }

        // Store route existence in each action definition
        foreach ($rows as &$row) {
            if (isset($row['actions']) && is_array($row['actions'])) {
                foreach ($row['actions'] as &$action) {
                    if (is_array($action) && isset($action['route']) && is_string($action['route'])) {
                        $action['route_exists'] = $routeExistsCache[$action['route']] ?? false;
                    }
                }
                unset($action); // Unset reference
            }
        }
        unset($row); // Unset reference

        return $rows;
    }
}
