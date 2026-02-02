<?php

namespace Dennenboom\VerdantUI\Tables;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Route;

final class DynamicTableCell
{
    public mixed $value;
    public bool $isActions;
    public bool $html = false;
    public string $class = '';
    public array $actions = [];

    public function __construct(mixed $value, bool $isActions, array $row)
    {
        $this->isActions = $isActions;
        $this->value = null;

        if ($isActions) {
            $this->resolveActionCellContent($value, $row);

            return;
        }

        $this->resolveRegularCellContent($value);
    }

    private function resolveActionCellContent(mixed $value, array $row): void
    {
        if ($value instanceof Htmlable) {
            $this->value = $value;

            return;
        }

        if (is_array($value)) {
            $this->actions = $this->resolveActions($value, $row);
        }

        if (isset($row['actions_render']) && $row['actions_render'] instanceof Htmlable) {
            $this->value = $row['actions_render'];
        }
    }

    private function resolveRegularCellContent(mixed $value): void
    {
        if (is_array($value)) {
            $this->value = $value['value'] ?? null;
            $this->class = $value['class'] ?? '';
            $this->html = $value['html'] ?? false;

            return;
        }

        $this->value = $value;
        $this->html = $value instanceof \Illuminate\Contracts\Support\Htmlable;
    }

    private function resolveActions(mixed $value, array $row): array
    {
        if (!is_array($value)) {
            return [];
        }

        $items = array_is_list($value) ? $value : [$value];

        return array_map(function ($action) use ($row) {
            $params = [];

            foreach ($action['params'] ?? [] as $k => $v) {
                $params[$k] = is_string($v) && array_key_exists($v, $row)
                    ? $row[$v]
                    : $v;
            }

            // Use cached route existence check if available, otherwise fall back to Route::has()
            $routeExists = $action['route_exists'] ?? Route::has($action['route'] ?? '');
            $routeName = $action['route'] ?? '';

            return [
                'label' => $action['label'] ?? '',
                'component' => 'v-button.' . ($action['button'] ?? 'secondary'),
                'href' => $routeExists && $routeName
                    ? route($routeName, $params)
                    : '#',
                'target' => $action['target'] ?? null,
            ];
        }, $items);
    }
}
