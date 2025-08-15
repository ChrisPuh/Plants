<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 */
class PlantContribution extends Model
{
    /** @use HasFactory<\Database\Factories\PlantContributionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plant_id',
        'field_name',
        'current_value',
        'proposed_value',
        'reason',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($contribution) {
            $validFieldNames = [
                'name', 'latin_name', 'family', 'genus', 'species', 'common_names',
                'description', 'botanical_description', 'category', 'plant_type',
                'growth_habit', 'native_region', 'height_min_cm', 'height_max_cm',
                'width_min_cm', 'width_max_cm', 'bloom_time', 'flower_color',
                'bark_type', 'foliage_type', 'is_edible', 'is_toxic', 'image_url',
                'propagation_methods', 'leaf_characteristics', 'flower_characteristics',
                'fruit_characteristics', 'images',
            ];

            if (! in_array($contribution->field_name, $validFieldNames)) {
                throw new \InvalidArgumentException("Invalid field name: {$contribution->field_name}");
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(User $admin, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    public function reject(User $admin, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    public function applyToPlant(): void
    {
        if (! $this->isApproved()) {
            throw new \Exception('Contribution must be approved before applying to plant');
        }

        $value = $this->proposed_value;

        // Handle JSON fields that need to be decoded
        $jsonFields = ['leaf_characteristics', 'flower_characteristics', 'fruit_characteristics', 'propagation_methods', 'images'];
        if (in_array($this->field_name, $jsonFields) && is_string($value)) {
            $value = json_decode($value, true) ?: [];
        }

        $this->plant->update([
            $this->field_name => $value,
        ]);
    }

    public function getFieldDisplayNameAttribute(): string
    {
        return match ($this->field_name) {
            'name' => 'Name',
            'latin_name' => 'Lateinischer Name',
            'family' => 'Familie',
            'genus' => 'Gattung',
            'species' => 'Art',
            'description' => 'Beschreibung',
            'botanical_description' => 'Botanische Beschreibung',
            'category' => 'Kategorie',
            'native_region' => 'Herkunftsregion',
            'flower_color' => 'Blütenfarbe',
            'bloom_time' => 'Blütezeit',
            default => ucfirst(str_replace('_', ' ', $this->field_name)),
        };
    }
}
