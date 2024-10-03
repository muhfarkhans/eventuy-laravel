<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventTicketCategory extends Model
{
    use HasFactory;

    protected $table = 'event_ticket_categories';

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'total_ticket',
        'is_publish',
        'color',
        'color_secondary',
        'benefits',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(EventTicket::class, 'event_id', 'id');
    }
}
