<?php

namespace Ticksya\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TicketPriority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'level',
        'response_time',
        'resolution_time',
    ];

    protected $casts = [
        'level' => 'integer',
        'response_time' => 'integer',
        'resolution_time' => 'integer',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'priority_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($priority) {
            if (! $priority->slug) {
                $priority->slug = Str::slug($priority->name);
            }
        });
    }
}
