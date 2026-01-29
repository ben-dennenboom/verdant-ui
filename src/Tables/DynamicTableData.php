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
            $headers[] = [
                'key' => $key,
                'label' => is_array($definition) ? $definition['label'] : $definition,
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
