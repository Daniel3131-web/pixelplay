<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentMatch extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentMatchFactory> */
    use HasFactory;

    //Tem que avisar que a tabela se chama 'matches' no database, pq tive que mudar o nome aqui
    protected $table = 'matches';
     protected $fillable = [
        'tournament_id',
        'map_id',
        'team_a_id',
        'team_b_id',
        'winner_id',
        'stage',
        'order_of_keys',
        'match_status',
    ];

    /**
     * Relacionamento: A partida pertence a um Torneio.
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Relacionamento: O Time A da partida.
     */
    public function teamA(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    /**
     * Relacionamento: O Time B da partida.
     */
    public function teamB(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

}
