<?php

namespace Dennenboom\VerdantUI\Tables;

use Illuminate\Database\Eloquent\Builder;

class DynamicTableBuilder
{
    public function build(
        Builder $query,
        string $tableClass,
        string $tableId,
        ?int $perPage = null,
    ): DynamicTableData {
        $columns = method_exists($tableClass, 'getColumns')
            ? $tableClass::getColumns()
            : [];

        $filters = method_exists($tableClass, 'filterDefinitions')
            ? $tableClass::filterDefinitions()
            : [];

        $searchable = method_exists($tableClass, 'searchableColumns')
            ? $tableClass::searchableColumns()
            : Column::searchableKeys($columns);

        if ($searchable === []) {
            $searchable = Column::searchableKeys($columns);
        }

        $allowedSortKeys = method_exists($tableClass, 'sortableColumns')
            ? $tableClass::sortableColumns()
            : Column::sortableKeys($columns);

        $defaultPerPage = $perPage ?? (method_exists($tableClass, 'perPage') ? $tableClass::perPage() : 20);

        $tableQuery = DynamicTableQuery::fromRequest($filters, $allowedSortKeys, $defaultPerPage);

        if ($tableQuery->sort->columns === [] && method_exists($tableClass, 'defaultSort')) {
            [$defaultColumn, $defaultDirection] = $tableClass::defaultSort();
            $query->orderBy($defaultColumn, $defaultDirection);
        }

        // Apply case-insensitive search. The vendor uses LIKE which is case-sensitive on PostgreSQL,
        // so we apply search ourselves and pass [] to prevent the vendor from re-applying it.
        if ($tableQuery->hasSearch() && $searchable !== []) {
            $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $this->applySearch($query, $searchable, $columns, $tableQuery->search, $operator);
        }

        DynamicTableQueryApplier::apply(
            query: $query,
            tableQuery: $tableQuery,
            columns: $columns,
            filters: $filters,
            searchable: [], // search already applied above
        );

        $paginator = $query->paginate($tableQuery->perPage ?? $defaultPerPage)->withQueryString();
        $tableData = $tableClass::make($paginator);

        $tableData->withSorting($tableQuery->sort);
        $tableData->withColumnVisibility(
            $tableId,
            method_exists($tableClass, 'defaultVisibleColumns') ? $tableClass::defaultVisibleColumns() : null
        );

        if ($searchable !== []) {
            $tableData->withSearchableColumns($searchable);
        }

        if ($filters !== []) {
            $tableData->withFilters($filters);
        }

        return $tableData;
    }

    /**
     * @param array<string> $searchable
     * @param array<Column> $columns
     */
    private function applySearch(Builder $query, array $searchable, array $columns, string $search, string $operator): void
    {
        $query->where(function ($builder) use ($searchable, $columns, $search, $operator) {
            foreach ($searchable as $key) {
                $definition = null;
                foreach ($columns as $col) {
                    if ($col instanceof Column && $col->getKey() === $key) {
                        $definition = $col->toDefinition();
                        break;
                    }
                }

                if ($definition !== null && isset($definition['search_query']) && is_callable($definition['search_query'])) {
                    ($definition['search_query'])($builder, $search);
                    continue;
                }

                $searchKey = Column::resolveSearchKey($key, $columns);
                $builder->orWhere($searchKey, $operator, '%' . $search . '%');
            }
        });
    }
}
