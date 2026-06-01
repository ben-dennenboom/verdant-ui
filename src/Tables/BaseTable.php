<?php

namespace Dennenboom\VerdantUI\Tables;

/**
 * Abstract base table for defining table columns and actions in a dedicated class.
 *
 * Subclass and implement columns(); override actions() or rowOpenUrl() when needed. Then:
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
        $data = DynamicTableData::fromCollection(
            $items,
            static::columns(),
            static::actions(),
            $context
        );

        $rowOpenUrl = static::rowOpenUrl();
        if ($rowOpenUrl !== null) {
            $data->withRowOpenUrl($rowOpenUrl);
        }

        $rowStyle = static::rowStyle();
        if ($rowStyle !== null) {
            $data->withRowStyle($rowStyle);
        }

        $bulkFields = static::bulkFields();
        if (!empty($bulkFields)) {
            $data->withBulkEdit($bulkFields, static::bulkActionUrl());
        }

        return $data->withActionsMaxVisible(static::actionsMaxVisible());
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

    /**
     * Optional URL to open when a row is double-clicked. Receives the same model instance as column value callables.
     * Return null or an empty string to disable navigation for that row.
     *
     * @return (callable(mixed): ?string)|null
     */
    protected static function rowOpenUrl(): ?callable
    {
        return null;
    }

    /**
     * Optional per-row style. Return a callable that receives the model and returns a RowStyle or null.
     * Supported variants: 'success' (green), 'info' (blue), 'warning' (yellow), 'danger' (red).
     * Pass bold: true to render cell text in semi-bold weight.
     *
     * @return (callable(mixed): ?RowStyle)|null
     */
    protected static function rowStyle(): ?callable
    {
        return null;
    }

    /**
     * Define bulk edit fields (BulkField instances).
     * Return a non-empty array to enable the bulk-edit floating bar.
     *
     * @return array<int, BulkField>
     */
    protected static function bulkFields(): array
    {
        return [];
    }

    /**
     * URL that the bulk-edit form POSTs to.
     * Receives _ids[] (selected row keys) plus each field value.
     */
    protected static function bulkActionUrl(): ?string
    {
        return null;
    }

    /**
     * Number of actions to show inline before overflow dropdown. Override to customize.
     */
    protected static function actionsMaxVisible(): int
    {
        return 2;
    }
}
