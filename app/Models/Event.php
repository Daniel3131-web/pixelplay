<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    /**
     * Atributos preenchíveis.
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'img',
        'max_participants',
        'current_participants',
        'type',
        'location',
        'streaming_url',
        'entrance_fee',
        'entry_date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'description',
    ];

    protected $casts = [
        'max_capacity' => 'integer',
        'entrance_fee' => 'decimal:2',
        'entry_date' => 'date:d/m/Y',
        'start_date' => 'date:d/m/Y',
        'end_date'   => 'date:d/m/Y',
    ];

    /**
     * Relacionamento: Um Evento pertence a um Organizador.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Relacionamento: O Evento possui muitos Torneios.
     */
    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class, 'event_id');
    }

}