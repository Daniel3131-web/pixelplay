<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


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
        'privacy',
        'password',
        'img'
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
            get: fn () => 5, // max 5 players
        );
    }
}
