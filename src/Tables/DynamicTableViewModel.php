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
        }

        if (is_array($data)) {
            if (isset($data['column_visibility_key'])) {
                $vm->columnVisibilityKey = $data['column_visibility_key'];
            }
            if (isset($data['default_visible_columns'])) {
                $vm->defaultVisibleColumns = $data['default_visible_columns'];
            }
        }

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
     * Resolve the column key for a given index (used for visibility and grid alignment).
     */
    public function columnKeyForIndex(int $index): string
    {
        $key = $this->columnKeys[$index] ?? null;

        return !is_null($key) ? (string) $key : 'col-' . $index;
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
