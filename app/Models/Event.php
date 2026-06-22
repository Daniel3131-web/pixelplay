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

    /**
     * O uso do cast 'date' transforma automaticamente as colunas em objetos Carbon.
     * Isso permite que você use métodos como ->format('d/m/Y') ou ->diffForHumans() no Blade.
     */
    protected $casts = [
        'max_capacity' => 'integer',
        'entrance_fee' => 'decimal:2',
        'entry_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
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

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('d/m/Y'),
        );
    }

    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('d/m/Y'),
        );
    }
}