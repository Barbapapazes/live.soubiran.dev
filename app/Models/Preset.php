<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Preset extends Model
{
    /** @use HasFactory<\Database\Factories\PresetFactory> */
    use HasFactory;

    /**
     * Get the user that owns the preset.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Activate the preset.
     */
    public function activate(): void
    {
        $this->update(['activated_at' => now()]);
    }

    /**
     * Check if the preset is activated.
     */
    public function isActivated(): bool
    {
        return $this->activated_at !== null;
    }

    public function casts(): array
    {
        return [
            'data' => 'array',
            'activated_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include activated presets.
     *
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function activated(Builder $query): void
    {
        $query->whereNotNull('activated_at');
    }
}
