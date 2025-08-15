<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantRequest extends Model
{
    /** @use HasFactory<\Database\Factories\PlantRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'latin_name',
        'family',
        'description',
        'reason',
        'proposed_data',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'created_plant_id',
    ];

    protected function casts(): array
    {
        return [
            'proposed_data' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function createdPlant(): BelongsTo
    {
        return $this->belongsTo(Plant::class, 'created_plant_id');
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

    public function createPlantFromRequest(): Plant
    {
        $plantData = array_merge($this->proposed_data ?? [], [
            'name' => $this->name,
            'latin_name' => $this->latin_name,
            'family' => $this->family,
            'description' => $this->description,
        ]);

        $plant = Plant::create($plantData);

        $this->update(['created_plant_id' => $plant->id]);

        return $plant;
    }
}
