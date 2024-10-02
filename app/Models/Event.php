<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'start_time',
        'end_time',
        'location',
        'location_gmaps',
        'total_ticket',
        'is_publish',
        'is_approved',
        'approved_by',
        'created_by',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): HasMany
    {
        return $this->hasMany(EventTicketCategory::class, 'event_id', 'id');
    }
}
