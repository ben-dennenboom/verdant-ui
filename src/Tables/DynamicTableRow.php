<?php

namespace Dennenboom\VerdantUI\Tables;

final class DynamicTableRow
{
    public array $cells;

    public ?string $openUrl = null;

    public ?string $rowKey = null;

    public ?RowStyle $rowStyle = null;

    public function __construct(array $rawRow, DynamicTableViewModel $vm)
    {
        $this->cells = [];

        if (array_key_exists('_row_open_url', $rawRow) && $rawRow['_row_open_url'] !== null && $rawRow['_row_open_url'] !== '') {
            $this->openUrl = (string) $rawRow['_row_open_url'];
        }

        if (array_key_exists('_row_key', $rawRow) && $rawRow['_row_key'] !== null && $rawRow['_row_key'] !== '') {
            $this->rowKey = (string) $rawRow['_row_key'];
        }

        if (array_key_exists('_row_style', $rawRow) && is_array($rawRow['_row_style'])) {
            $this->rowStyle = RowStyle::fromArray($rawRow['_row_style']);
        }

        foreach ($vm->columnKeys as $index => $key) {
            $this->cells[] = new DynamicTableCell(
                $key !== null
                    ? ($rawRow[$key] ?? null)
                    : ($rawRow[$index] ?? null),
                $key === 'actions',
                $rawRow
            );
        }
    }
}
