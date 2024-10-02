<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
