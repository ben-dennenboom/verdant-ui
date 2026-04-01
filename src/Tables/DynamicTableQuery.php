<?php

namespace Dennenboom\VerdantUI\Tables;

final class DynamicTableQuery
{
    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(
        public readonly string $search,
        public readonly array $filters,
        public readonly DynamicTableSort $sort,
        public readonly ?int $perPage = null,
    ) {
    }

    /**
     * @param iterable<Filter|array<string, mixed>> $filters
     * @param array<string>|null $allowedSortKeys
     */
    public static function fromRequest(
        iterable $filters = [],
        ?array $allowedSortKeys = null,
        ?int $defaultPerPage = null,
    ): self {
        $filterDefinitions = self::normalizeFilters($filters);

        return new self(
            search: trim((string)request('search', '')),
            filters: self::resolveFilterValues($filterDefinitions),
            sort: DynamicTableSort::fromRequest($allowedSortKeys),
            perPage: self::resolvePerPage($defaultPerPage),
        );
    }

    public function hasSearch(): bool
    {
        return $this->search !== '';
    }

    public function hasFilter(string $key): bool
    {
        return array_key_exists($key, $this->filters);
    }

    public function filterValue(string $key): mixed
    {
        return $this->filters[$key] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function activeFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param iterable<Filter|array<string, mixed>> $filters
     * @return array<int, array<string, mixed>>
     */
    private static function normalizeFilters(iterable $filters): array
    {
        $normalized = [];

        foreach ($filters as $filter) {
            $definition = $filter instanceof Filter
                ? $filter->toDefinition()
                : (is_array($filter) ? $filter : null);

            if (!is_array($definition) || !isset($definition['key'])) {
                continue;
            }

            $normalized[] = $definition;
        }

        return $normalized;
    }

    /**
     * @param array<int, array<string, mixed>> $filters
     * @return array<string, mixed>
     */
    private static function resolveFilterValues(array $filters): array
    {
        $values = [];
        $hasExplicitFilters = false;

        foreach ($filters as $filter) {
            $key = $filter['key'] ?? null;

            if (!is_string($key) || !request()->has($key)) {
                continue;
            }

            $value = self::normalizeFilterValue(request()->get($key), !empty($filter['multiple']));

            if ($value === null) {
                continue;
            }

            $hasExplicitFilters = true;
            $values[$key] = $value;
        }

        if ($hasExplicitFilters) {
            return $values;
        }

        foreach ($filters as $filter) {
            $key = $filter['key'] ?? null;

            if (!is_string($key) || !array_key_exists('default', $filter)) {
                continue;
            }

            $value = self::normalizeFilterValue($filter['default'], !empty($filter['multiple']));

            if ($value === null) {
                continue;
            }

            $values[$key] = $value;
        }

        return $values;
    }

    private static function resolvePerPage(?int $defaultPerPage): ?int
    {
        $value = request('per_page');

        if ($value === null || $value === '') {
            return $defaultPerPage;
        }

        $perPage = (int)$value;

        return $perPage > 0 ? $perPage : $defaultPerPage;
    }

    private static function normalizeFilterValue(mixed $value, bool $multiple): mixed
    {
        if ($multiple || is_array($value)) {
            $value = array_values(array_filter((array)$value, static fn($item) => filled($item)));

            return $value === [] ? null : $value;
        }

        return filled($value) ? $value : null;
    }
}
