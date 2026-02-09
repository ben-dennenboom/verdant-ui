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

    public static function fromCollection(
        iterable $items,
        array $columns,
        ?callable $actions = null
    ): self {
        if (empty($columns)) {
            return new self([], []);
        }

        $instance = new self([], []);

        if ($items instanceof AbstractPaginator) {
            $instance->paginator = $items;
            $collection = $items->getCollection();
        } else {
            $collection = collect($items);
        }

        $headers = [];
        foreach ($columns as $key => $definition) {
            $headers[] = is_array($definition) ?
                array_merge(
                    [
                        'key'   => $key,
                        'label' => $definition['label'],
                    ],
                    array_intersect_key(
                        $definition,
                        ['sortable' => true]
                    )
                ) : [
                    'key'   => $key,
                    'label' => $definition,
                ];
        }

        $rows = $collection->map(function ($model) use ($columns, $actions) {
            $row = [];

            foreach ($columns as $key => $definition) {
                $value = data_get($model, $key);

                if (is_array($definition)) {
                    if (isset($definition['format'])) {
                        $value = ($definition['format'])($value, $model);
                    }

                    if (isset($definition['render'])) {
                        $value = ($definition['render'])($value, $model);
                    }
                }

                $row[$key] = $value;
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
     * @param  array<int, array<string, mixed>>  $filters
     */
    public function withFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @param  array<string>|null  $defaultVisible
     */
    public function withColumnVisibility(string $key, ?array $defaultVisible = null): self
    {
        $this->columnVisibilityKey = $key;
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
                    $av = data_get($a, $col['key']);
                    $bv = data_get($b, $col['key']);

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
