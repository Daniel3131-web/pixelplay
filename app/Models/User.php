<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'img',
        'team_id',
        'role',
        'events',
        'tournaments',
        'matches',
        'wins',
    ];

    /**
     * Relacionamento: O User pertence a varias inboxes (notificações).
     */
    public function inboxes()
    {
        return $this->hasMany(Inbox::class)->latest();
    }

    /**
     * Relacionamento: O User pertence a um Time.
     */
    public function User_Team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Relacionamento: Um usuário pode ter várias ordens (ingressos/pagamentos).
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function user_events()
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    // Verifica se tem ingresso para o Evento
    public function hasTicketForEvent(int $eventId)
    {
        return $this->orders()->where('event_id', $eventId)->where('status', 'pago')->exists();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
