<?php

namespace Dennenboom\VerdantUI\Tables;

/**
 * Abstract base table for defining table columns and actions in a dedicated class.
 *
 * Subclass and implement columns(); override actions() when needed. Then:
 *
 *   $tableData = MyTable::make($paginator)->withSorting($sort)->withColumnVisibility('key', null);
 *
 * columns() may return an array of Column DTOs or the legacy key => definition format.
 */
abstract class BaseTable
{
    /**
     * Build table data from a paginator, collection, or any iterable.
     *
     * @param  iterable<mixed>  $items  Paginator, Collection, array, or other iterable of models
     * @param  mixed  $context  Optional context for Column::visibleWhen() (e.g. auth user)
     */
    final public static function make(iterable $items, mixed $context = null): DynamicTableData
    {
        return DynamicTableData::fromCollection(
            $items,
            static::columns(),
            static::actions(),
            $context
        );
    }

    /**
     * Define table columns (Column instances or key => definition array).
     *
     * @return array<int|string, Column|string|array<string, mixed>>
     */
    abstract protected static function columns(): array;

    /**
     * Define row actions (callable receiving the model, returning action array or render).
     *
     * @return callable|null
     */
    protected static function actions(): ?callable
    {
        return null;
    }
}
