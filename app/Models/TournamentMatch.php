<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class TournamentMatch extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentMatchFactory> */
    use HasFactory;

    //Tem que avisar que a tabela se chama 'matches' no database, pq tive que mudar o nome aqui
    protected $table = 'matches';
     protected $fillable = [
        'id',
        'tournament_id',
        'map_id',
        'team_a_id',
        'team_b_id',
        'score_a',
        'score_b',
        'winner_id',
        'stage',
        'order_of_keys',
        'match_status',
    ];

    /**
     * Relacionamento: A partida pertence a um mapa.
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

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

    /**
     * Relacionamento: O time vencedor da partida.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    /**
    * Relacionamento: Estatísticas (Player Infos) desta partida
    */
    public function player_Infos(): HasMany
    {
        return $this->hasMany(PlayerInfos::class, 'match_id');
    }

}
