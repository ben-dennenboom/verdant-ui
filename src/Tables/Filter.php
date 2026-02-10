<?php

namespace Dennenboom\VerdantUI\Tables;

final class Filter
{
    private const TYPES = ['text', 'number', 'date', 'checkbox', 'select'];

    private string $key;

    private string $label;

    private string $type;

    /** @var array<int|string, mixed>|callable|null */
    private mixed $options = null;

    private ?string $placeholder = null;

    private mixed $default = null;

    private bool $multiple = false;

    private function __construct(string $key, string $label, string $type)
    {
        $type = strtolower($type);
        if (! in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException(
                'Filter type must be one of: ' . implode(', ', self::TYPES) . ', got: ' . $type
            );
        }
        $this->key = $key;
        $this->label = $label;
        $this->type = $type;
    }

    public static function make(string $key, string $label, string $type): self
    {
        return new self($key, $label, $type);
    }

    /**
     * Set options for select type. Array format: value => label or list of ['value' => x, 'label' => y].
     * Callable is invoked in toDefinition() so options can be resolved at build time.
     *
     * @param  array<int|string, mixed>|callable  $options
     */
    public function options(array|callable $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set placeholder for text/number inputs.
     */
    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Set default value when no request value is present.
     */
    public function default(mixed $value): self
    {
        $this->default = $value;

        return $this;
    }

    /**
     * Allow multiple values for select (sends key as array).
     */
    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Convert to the definition array expected by DynamicTableData / DynamicTableViewModel.
     *
     * @return array<string, mixed>
     */
    public function toDefinition(): array
    {
        $definition = [
            'key' => $this->key,
            'label' => $this->label,
            'type' => $this->type,
        ];

        if ($this->placeholder !== null) {
            $definition['placeholder'] = $this->placeholder;
        }

        if ($this->default !== null) {
            $definition['default'] = $this->default;
        }

        if ($this->multiple) {
            $definition['multiple'] = true;
        }

        if ($this->options !== null && $this->type === 'select') {
            $options = is_callable($this->options)
                ? ($this->options)()
                : $this->options;
            $definition['options'] = is_array($options) ? $options : [];
        }

        return $definition;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
