<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PlayerInfos extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerInfosFactory> */
    use HasFactory;

    protected $table = 'player_infos';

    protected $fillable = [
        'user_id',
        'team_id',
        'match_id',
        'kill',
        'death',
        'assistance',
        'gold',
        'score',
    ];


    /**
     * Relacionamento: O player info pertence a um player.
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacionamento: O player info pertence a um time (o time que o player jogou no momento).
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relacionamento: O player info pertence a uma partida.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(TournamentMatch::class);
    }

}
