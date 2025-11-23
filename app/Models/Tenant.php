<?php

namespace App\Models;

use App\Enums\DatabaseType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'database_type',
        'database_name',
        'database_username',
        'database_password',
        'database',
    ];

    protected $casts = [
        'database_type' => DatabaseType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Check if this tenant uses multi-database mode.
     */
    public function isMultiDatabase(): bool
    {
        return $this->database_type === DatabaseType::MULTI;
    }

    /**
     * Check if this tenant uses single database mode.
     */
    public function isSingleDatabase(): bool
    {
        return $this->database_type === DatabaseType::SINGLE;
    }

    /**
     * Get the database type label.
     */
    public function getDatabaseTypeLabel(): string
    {
        return $this->database_type->label();
    }

    /**
     * Get the database type description.
     */
    public function getDatabaseTypeDescription(): string
    {
        return $this->database_type->description();
    }

    /**
     * Get the database configuration for this tenant.
     */
    public function getDatabaseConfig(): array
    {
        if ($this->isMultiDatabase()) {
            return [
                'driver' => config('database.connections.mysql.driver', 'mysql'),
                'host' => config('database.connections.mysql.host', '127.0.0.1'),
                'port' => config('database.connections.mysql.port', '3306'),
                'database' => $this->database_name,
                'username' => $this->database_username,
                'password' => $this->database_password,
                'charset' => config('database.connections.mysql.charset', 'utf8mb4'),
                'collation' => config('database.connections.mysql.collation', 'utf8mb4_unicode_ci'),
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ];
        }

        // Single database mode - return default connection config
        return config('database.connections.mysql');
    }

    /**
     * Create the tenant database if in multi-database mode.
     */
    public function createDatabase(): void
    {
        if ($this->isMultiDatabase() && $this->database_name) {
            $charset = config('database.connections.mysql.charset', 'utf8mb4');
            $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

            DB::statement("CREATE DATABASE IF NOT EXISTS `{$this->database_name}` CHARACTER SET {$charset} COLLATE {$collation}");
            
            // Optionally create database user (if needed)
            // This is commented out for security - enable only if you need it
            // DB::statement("CREATE USER IF NOT EXISTS '{$this->database_username}'@'%' IDENTIFIED BY '{$this->database_password}'");
            // DB::statement("GRANT ALL PRIVILEGES ON `{$this->database_name}`.* TO '{$this->database_username}'@'%'");
            // DB::statement("FLUSH PRIVILEGES");
        }
    }

    /**
     * Drop the tenant database if in multi-database mode.
     */
    public function dropDatabase(): void
    {
        if ($this->isMultiDatabase() && $this->database_name) {
            DB::statement("DROP DATABASE IF EXISTS `{$this->database_name}`");
        }
    }

    /**
     * Get the name of the database this tenant should use.
     */
    public function getDatabaseName(): string
    {
        if ($this->isMultiDatabase()) {
            return $this->database_name;
        }

        // Single database mode - use the main database
        return config('database.connections.mysql.database');
    }

    /**
     * Execute a callback within this tenant's context.
     */
    public function execute(callable $callback): mixed
    {
        return $this->makeCurrent()->callback();
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // Auto-create database when tenant is created in multi-database mode
        static::created(function (Tenant $tenant) {
            if ($tenant->isMultiDatabase()) {
                $tenant->createDatabase();
            }
        });

        // Auto-drop database when tenant is deleted in multi-database mode
        static::deleting(function (Tenant $tenant) {
            if ($tenant->isMultiDatabase()) {
                // Uncomment to auto-drop databases
                // $tenant->dropDatabase();
            }
        });
    }
}

