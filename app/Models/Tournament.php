<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Tournament extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'category',
        'max_participants',
        'current_participants',
        'live',
        'status',
        'entrance_fee',
        'awards',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'entry_date',
        'img'
    ];

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d/m/Y'),
        );
    }

    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d/m/Y'),
        );
    }

    /**
    * Relacionamento: Um torneio possui muitas partidas.
    */
    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'tournament_id');
    }
}
