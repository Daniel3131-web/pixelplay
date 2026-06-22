<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'acronym',
        'description',
        'privacy',
        'password',
        'img',
        'leader_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    // Relacionamento
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'team_id');
    }

    /**
     * Retorna a quantidade atual de participantes no time.
     */
    protected function currentParticipants(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (isset($this->users_count)) {
                    return $this->users_count;
                }
                return $this->users()->count();
            }
        );
    }

    /**
     * Retorna a quantidade maxima de participantes no time.
     */
    protected function maxParticipants(): Attribute
    {
        return Attribute::make(
            get: fn() => 5, // max 5 players
        );
    }

    /**
     *  Relacionamento um time tem varios torneios e um torneio tem varios times N:N.
     */
    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class, 'tournament_team', 'team_id', 'tournament_id');
    }


    /**
     *  Verifica se todos os membros tem um ingresso
     */
    public function allMembersHaveTickets($eventId)
    {
        $membersWithTickets = $this->users()->whereHas('orders', function ($query) use ($eventId) {
            $query->where('event_id', $eventId)
                ->where('status', 'pago');
        })->count();

        return $membersWithTickets === $this->users->count();
    }
}
