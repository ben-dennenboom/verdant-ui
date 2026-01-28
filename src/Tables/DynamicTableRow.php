<?php

namespace Dennenboom\VerdantUI\Tables;

final class DynamicTableRow
{
    public array $cells;

    public function __construct(array $rawRow, DynamicTableViewModel $vm)
    {
        $this->cells = [];

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
