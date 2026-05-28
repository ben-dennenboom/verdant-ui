<?php

namespace Dennenboom\VerdantUI\Tables;

/**
 * Describes the visual style applied to a table row.
 *
 * Variants map to named colour schemes defined in the blade templates:
 *   'success'  → green background
 *   'info'     → blue background
 *   'warning'  → yellow background
 *   'danger'   → red background
 *
 * Set $bold = true to render all cell text in semi-bold weight.
 *
 * Usage (in a BaseTable subclass):
 *
 *   protected static function rowStyle(): ?callable
 *   {
 *       return static fn($model) => $model->isUrgent()
 *           ? new RowStyle('danger', bold: true)
 *           : null;
 *   }
 */
final class RowStyle
{
    public function __construct(
        public readonly string $variant,
        public readonly bool $bold = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            variant: (string) ($data['variant'] ?? ''),
            bold: (bool) ($data['bold'] ?? false),
        );
    }

    public function toArray(): array
    {
        return ['variant' => $this->variant, 'bold' => $this->bold];
    }
}
