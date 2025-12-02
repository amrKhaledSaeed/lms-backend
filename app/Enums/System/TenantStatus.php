<?php

namespace App\Enums\System;

enum TenantStatus: int
{
    case ACTIVE = 1;
    case SUSPENDED = 2;
    case ARCHIVED = 3;

    /**
     * Get the display label for the enum value.
     */
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::SUSPENDED => 'Suspended',
            self::ARCHIVED => 'Archived',
        };
    }

    /**
     * Get the color class for UI display.
     */
    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::SUSPENDED => 'warning',
            self::ARCHIVED => 'secondary',
        };
    }

    /**
     * Check if tenant is active.
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Check if tenant can be accessed.
     */
    public function canAccess(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Get all enum values as array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all enum cases with their labels for dropdowns.
     */
    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'color' => $case->color(),
        ], self::cases());
    }

    /**
     * Get all enum cases as array with full details.
     */
    public static function toArray(): array
    {
        return array_map(fn(self $status) => [
            'value' => $status->value,
            'label' => $status->label(),
            'color' => $status->color(),
            'is_active' => $status->isActive(),
            'can_access' => $status->canAccess(),
        ], self::cases());
    }
}

