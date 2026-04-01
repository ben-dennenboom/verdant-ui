<?php

namespace Dennenboom\VerdantUI\Tables;

final class DynamicTableQueryApplier
{
    /**
     * @param \Illuminate\Contracts\Database\Query\Builder $query
     * @param array<int|string, Column|string|array<string, mixed>> $columns
     * @param array<int, Filter|array<string, mixed>> $filters
     * @param array<int, string>|null $searchable
     */
    public static function apply(
        $query,
        DynamicTableQuery $tableQuery,
        array $columns = [],
        array $filters = [],
        ?array $searchable = null,
    ): void {
        self::applySearch($query, $tableQuery, $columns, $searchable);
        self::applyFilters($query, $tableQuery, $filters);
        self::applySort($query, $tableQuery, $columns);
    }

    /**
     * @param \Illuminate\Contracts\Database\Query\Builder $query
     * @param array<int|string, Column|string|array<string, mixed>> $columns
     * @param array<int, string>|null $searchable
     */
    private static function applySearch(
        $query,
        DynamicTableQuery $tableQuery,
        array $columns,
        ?array $searchable,
    ): void {
        if (!$tableQuery->hasSearch()) {
            return;
        }

        $normalizedColumns = self::normalizeColumns($columns);
        $searchableKeys = $searchable ?? Column::searchableKeys($normalizedColumns);

        if ($searchableKeys === []) {
            return;
        }

        $search = $tableQuery->search;

        $query->where(function ($builder) use ($searchableKeys, $normalizedColumns, $search) {
            foreach ($searchableKeys as $key) {
                if ($normalizedColumns !== []) {
                    Column::applySearch($key, $normalizedColumns, $builder, $search);
                    continue;
                }

                $builder->orWhere($key, 'like', '%' . $search . '%');
            }
        });
    }

    /**
     * @param \Illuminate\Contracts\Database\Query\Builder $query
     * @param array<int, Filter|array<string, mixed>> $filters
     */
    private static function applyFilters($query, DynamicTableQuery $tableQuery, array $filters): void
    {
        if ($filters === []) {
            return;
        }

        foreach ($tableQuery->activeFilters() as $key => $value) {
            Filter::apply($key, $filters, $query, $value);
        }
    }

    /**
     * @param \Illuminate\Contracts\Database\Query\Builder $query
     * @param array<int|string, Column|string|array<string, mixed>> $columns
     */
    private static function applySort($query, DynamicTableQuery $tableQuery, array $columns): void
    {
        foreach ($tableQuery->sort->columns as $column) {
            $direction = $column['direction'] ?? 'asc';

            if ($columns !== []) {
                Column::applySort($column['key'], $columns, $query, $direction);
                continue;
            }

            $query->orderBy($column['key'], $direction);
        }
    }

    /**
     * @param array<int|string, Column|string|array<string, mixed>> $columns
     * @return array<string, string|array<string, mixed>>
     */
    private static function normalizeColumns(array $columns): array
    {
        $normalized = [];

        foreach ($columns as $key => $column) {
            if ($column instanceof Column) {
                $normalized[$column->getKey()] = $column->toDefinition();
                continue;
            }

            if (is_string($key) && (is_string($column) || is_array($column))) {
                $normalized[$key] = $column;
            }
        }

        return $normalized;
    }
}
