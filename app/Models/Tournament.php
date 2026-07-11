<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Tournament extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'category',
        'max_participants',
        'current_participants',
        'bracket_generated_at',
        'entrance_fee',
        'awards',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'entry_date',
        // 'status',
        'img'
    ];

    protected $casts = [
        'entry_date' => 'date:d/m/Y',
        'start_date' => 'date:d/m/Y',
        'end_date' => 'date:d/m/Y',
    ];

    /**
     * Relacionamento: Um torneio possui muitas partidas.
     */
    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'tournament_id');
    }

    /**
     * Relacionamento: Um torneio possui muitos times e um time possui muitos torneios N:N.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'tournament_team', 'tournament_id', 'team_id');
    }

}
