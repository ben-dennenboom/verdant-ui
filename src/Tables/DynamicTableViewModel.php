<?php

namespace Dennenboom\VerdantUI\Tables;

use Dennenboom\VerdantUI\Contracts\DynamicTableDataProvider;
use Illuminate\Pagination\AbstractPaginator;

final class DynamicTableViewModel
{
    public array $headers;
    public array $rows;
    public array $columnKeys;
    public int $columnCount;
    public bool $usesKeys;
    public ?int $actionsColumnIndex;
    public ?AbstractPaginator $paginator = null;
    public ?DynamicTableSort $sort = null;
    public ?string $columnVisibilityKey = null;
    public ?array $defaultVisibleColumns = null;
    public ?array $searchableColumns = null;
    public string $searchTerm = '';
    public ?string $searchApiUrl = null;
    /** @var array<int, array<string, mixed>>|null */
    public ?array $filterColumns = null;
    /** @var array<string, mixed>|null */
    public ?array $filterValues = null;

    /** Number of actions to show inline before overflow dropdown. Null = use default (2). */
    public ?int $actionsMaxVisible = null;

    public bool $rowInteractionEnabled = false;

    /** @var array<int, array<string, mixed>>|null */
    public ?array $bulkFields = null;

    public ?string $bulkActionUrl = null;

    public bool $hasBulkEdit = false;

    /** @var array<int, string> Primary keys of all visible rows, for select-all. */
    public array $allRowKeys = [];

    /**
     * grid-template-columns value derived from header width hints (fixed widths + minmax(0,1fr) for the rest).
     */
    public string $gridTemplateColumns = '';

    /**
     * Parallel to headers / columnKeys: CSS length for fixed tracks, null for flexible columns.
     *
     * @var array<int, string|null>
     */
    public array $columnGridWidths = [];

    private const ACTIONS_KEY = 'actions';

    private function __construct() {}

    public static function from(
        DynamicTableDataProvider|array|null $data,
        array $fallbackHeaders = [],
        array $fallbackRows = []
    ): self {
        $vm = new self();

        [$headers, $rows] = self::resolveSource($data, $fallbackHeaders, $fallbackRows);

        $vm->headers = self::normalizeHeaders($headers, $rows);
        $vm->columnKeys = self::extractColumnKeys($vm->headers);
        $vm->usesKeys = in_array(true, array_map(fn ($k) => !is_null($k), $vm->columnKeys), true);

        $idx = array_search(self::ACTIONS_KEY, $vm->columnKeys, true);
        $vm->actionsColumnIndex = $idx !== false ? $idx : null;

        $vm->rows = self::normalizeRows($rows, $vm);
        $vm->columnCount = count($vm->headers);
        $vm->allRowKeys = array_values(array_filter(array_map(fn ($r) => $r->rowKey, $vm->rows)));
        $vm->columnGridWidths = self::extractColumnGridWidths($vm->headers);
        $vm->gridTemplateColumns = self::buildGridTemplateColumnsFromWidths($vm->columnGridWidths);

        if ($data instanceof DynamicTableDataProvider && !is_null($data->sort())) {
            $vm->sort = $data->sort();
        }

        if ($data instanceof DynamicTableDataProvider) {
            $vm->paginator = $data->paginator();

            $visibilityKey = $data->columnVisibilityKey();
            if (!is_null($visibilityKey)) {
                $vm->columnVisibilityKey = $visibilityKey;
            }

            $defaultVisible = $data->defaultVisibleColumns();
            if (!is_null($defaultVisible)) {
                $vm->defaultVisibleColumns = $defaultVisible;
            }

            $searchable = $data->searchableColumns();
            if (!is_null($searchable)) {
                $vm->searchableColumns = $searchable;
            }

            $apiUrl = $data->searchApiUrl();
            if (!is_null($apiUrl)) {
                $vm->searchApiUrl = $apiUrl;
            }

            $filterCols = $data->filterColumns();
            if (!is_null($filterCols) && $filterCols !== []) {
                $vm->filterColumns = self::normalizeFilterColumns($filterCols);
            }

            $maxVisible = $data->actionsMaxVisible();
            if (!is_null($maxVisible)) {
                $vm->actionsMaxVisible = $maxVisible;
            }

            $vm->rowInteractionEnabled = $data->rowInteractionEnabled();

            $bulkFields = $data->bulkFields();
            if (!empty($bulkFields)) {
                $vm->bulkFields = self::normalizeBulkFields($bulkFields);
                $vm->bulkActionUrl = $data->bulkActionUrl();
                $vm->hasBulkEdit = true;
            }
        }

        if (is_array($data)) {
            if (isset($data['column_visibility_key'])) {
                $vm->columnVisibilityKey = $data['column_visibility_key'];
            }
            if (isset($data['default_visible_columns'])) {
                $vm->defaultVisibleColumns = $data['default_visible_columns'];
            }
            if (isset($data['searchable_columns'])) {
                $vm->searchableColumns = $data['searchable_columns'];
            }
            if (isset($data['search_api_url'])) {
                $vm->searchApiUrl = $data['search_api_url'];
            }
            if (isset($data['filters']) && is_array($data['filters']) && $data['filters'] !== []) {
                $vm->filterColumns = self::normalizeFilterColumns($data['filters']);
            }
            if (isset($data['actions_max_visible']) && is_int($data['actions_max_visible'])) {
                $vm->actionsMaxVisible = $data['actions_max_visible'];
            }
            if (isset($data['bulk_fields']) && is_array($data['bulk_fields']) && $data['bulk_fields'] !== []) {
                $vm->bulkFields = self::normalizeBulkFields($data['bulk_fields']);
                $vm->bulkActionUrl = $data['bulk_action_url'] ?? null;
                $vm->hasBulkEdit = true;
            }
        }

        $vm->filterValues = $vm->filterColumns !== null
            ? self::resolveFilterValues($vm->filterColumns)
            : null;

        $vm->searchTerm = (string) (is_array($data) && isset($data['search_term'])
            ? $data['search_term']
            : request('search', ''));

        return $vm;
    }

    private static function resolveSource(
        DynamicTableDataProvider|array|null $data,
        array $headers,
        array $rows
    ): array {
        if ($data instanceof DynamicTableDataProvider) {
            return [$data->headers(), $data->rows()];
        }

        if (is_array($data)) {
            return [$data['headers'] ?? $headers, $data['rows'] ?? $rows];
        }

        return [$headers, $rows];
    }

    private static function normalizeHeaders(array $headers, array $rows): array
    {
        $normalized = [];

        foreach ($headers as $header) {
            $normalized[] = is_array($header)
                ? $header
                : ['label' => $header, 'key' => null];
        }

        if (self::rowsContainActions($rows)
            && !collect($normalized)->contains(fn ($h) => ($h['key'] ?? null) === self::ACTIONS_KEY)
        ) {
            $normalized[] = [
                'label' => 'Actions',
                'key' => self::ACTIONS_KEY,
                'class' => 'v-text-center',
                'sortable' => false,
            ];
        }

        return $normalized;
    }

    private static function extractColumnKeys(array $headers): array
    {
        return array_map(fn ($h) => $h['key'] ?? null, $headers);
    }

    private static function rowsContainActions(array $rows): bool
    {
        foreach ($rows as $row) {
            if (is_array($row) && array_key_exists(self::ACTIONS_KEY, $row)) {
                return true;
            }
        }

        return false;
    }

    private static function normalizeRows(array $rows, self $vm): array
    {
        return array_map(function ($row) use ($vm) {
            return new DynamicTableRow($row, $vm);
        }, $rows);
    }

    /**
     * @param array<int, array<string, mixed>> $fields
     * @return array<int, array<string, mixed>>
     */
    private static function normalizeBulkFields(array $fields): array
    {
        $normalized = [];
        foreach ($fields as $field) {
            if (!is_array($field) || empty($field['key']) || empty($field['label']) || empty($field['type'])) {
                continue;
            }
            $item = [
                'key'         => (string) $field['key'],
                'label'       => (string) $field['label'],
                'type'        => (string) $field['type'],
                'placeholder' => $field['placeholder'] ?? null,
                'multiple'    => !empty($field['multiple']),
            ];
            if (($field['type'] ?? '') === 'select' && isset($field['options']) && is_array($field['options'])) {
                $item['options'] = self::normalizeSelectOptions($field['options']);
            } else {
                $item['options'] = null;
            }
            $normalized[] = $item;
        }

        return $normalized;
    }

    /**
     * @param array<Filter> $filters
     * @return array<int, array<string, mixed>>
     */
    private static function normalizeFilterColumns(array $filters): array
    {
        $normalized = [];
        foreach ($filters as $filter) {
            if ($filter instanceof Filter) {
                $filter = $filter->toDefinition();
            }
            if (!is_array($filter) || empty($filter['key']) || empty($filter['label']) || empty($filter['type'])) {
                continue;
            }
            $item = [
                'key' => (string) $filter['key'],
                'label' => (string) $filter['label'],
                'type' => (string) $filter['type'],
                'placeholder' => $filter['placeholder'] ?? null,
                'default' => $filter['default'] ?? null,
                'multiple' => !empty($filter['multiple']),
            ];
            if (($filter['type'] ?? '') === 'select' && isset($filter['options']) && is_array($filter['options'])) {
                $item['options'] = self::normalizeSelectOptions($filter['options']);
            } else {
                $item['options'] = null;
            }
            $normalized[] = $item;
        }

        return $normalized;
    }

    /**
     * @param array<int|string, mixed> $options
     * @return array<int, array{value: string|int|float, label: string}>
     */
    private static function normalizeSelectOptions(array $options): array
    {
        $out = [];
        foreach ($options as $value => $label) {
            if (is_array($label) && isset($label['value'], $label['label'])) {
                $out[] = ['value' => $label['value'], 'label' => (string) $label['label']];
            } elseif (is_int($value)) {
                $out[] = ['value' => $label, 'label' => (string) $label];
            } else {
                $out[] = ['value' => $value, 'label' => (string) $label];
            }
        }

        return $out;
    }

    /**
     * @param array<int, array<string, mixed>> $filterColumns
     * @return array<string, mixed>
     */
    private static function resolveFilterValues(array $filterColumns): array
    {
        $values = [];
        foreach ($filterColumns as $col) {
            $key = $col['key'] ?? null;
            if ($key === null) {
                continue;
            }
            $default = $col['default'] ?? null;
            if (!empty($col['multiple'])) {
                $values[$key] = request()->has($key) ? (array) request($key) : (is_array($default) ? $default : []);
            } else {
                $values[$key] = request($key, $default);
            }
        }

        return $values;
    }

    /**
     * Resolve the column key for a given index (used for visibility and grid alignment).
     */
    public function columnKeyForIndex(int $index): string
    {
        $key = $this->columnKeys[$index] ?? null;

        return !is_null($key) ? (string) $key : 'col-' . $index;
    }

    /**
     * @param  array<int, array<string, mixed>|string>  $headers
     * @return array<int, string|null>
     */
    private static function extractColumnGridWidths(array $headers): array
    {
        $widths = [];
        foreach ($headers as $header) {
            if (is_array($header) && ! empty($header['width'])) {
                $widths[] = (string) $header['width'];
            } else {
                $widths[] = null;
            }
        }

        return $widths;
    }

    /**
     * @param  array<int, string|null>  $widths
     */
    private static function buildGridTemplateColumnsFromWidths(array $widths): string
    {
        if ($widths === []) {
            return '';
        }

        $parts = [];
        foreach ($widths as $w) {
            $parts[] = ($w !== null && $w !== '') ? $w : 'minmax(0, 1fr)';
        }

        return implode(' ', $parts);
    }

    /**
     * Resolve the header label for a given column index.
     */
    public function headerLabel(int $index): string
    {
        $header = $this->headers[$index] ?? null;

        if (is_null($header)) {
            return (string) $index;
        }

        return is_array($header)
            ? (string) ($header['label'] ?? $index)
            : (string) $header;
    }
}
