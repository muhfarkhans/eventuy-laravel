<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'email',
        'address',
        'thumbnail',
        'organization_type',
        'key_activities',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_organizations', 'organization_id', 'user_id')
            ->withTimestamps();
    }
}
