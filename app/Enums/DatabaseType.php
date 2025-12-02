<?php

namespace App\Enums;

enum DatabaseType: int
{
    case SINGLE = 1;
    case MULTI = 2;

    /**
     * Get the display label for the enum value.
     */
    public function label(): string
    {
        return match($this) {
            self::SINGLE => 'Single Database',
            self::MULTI => 'Multi Database',
        };
    }

    /**
     * Get the description for the enum value.
     */
    public function description(): string
    {
        return match($this) {
            self::SINGLE => 'All tenants share the main database',
            self::MULTI => 'Each tenant has a separate database',
        };
    }

    /**
     * Get the value as string (for backward compatibility).
     */
    public function stringValue(): string
    {
        return match($this) {
            self::SINGLE => 'single',
            self::MULTI => 'multi',
        };
    }

    /**
     * Get all enum cases with their labels.
     */
    public static function options(): array
    {
        return [
            self::SINGLE->value => self::SINGLE->label(),
            self::MULTI->value => self::MULTI->label(),
        ];
    }

    /**
     * Get all enum cases with value and label.
     */
    public static function toArray(): array
    {
        return array_map(fn(self $type) => [
            'value' => $type->value,
            'label' => $type->label(),
            'string_value' => $type->stringValue(),
            'description' => $type->description(),
        ], self::cases());
    }

    /**
     * Create enum from string value.
     */
    public static function fromString(string $value): ?self
    {
        return match(strtolower($value)) {
            'single' => self::SINGLE,
            'multi' => self::MULTI,
            default => null,
        };
    }
}


