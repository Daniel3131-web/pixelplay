<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    use HasFactory;

    // Define explicitamente o nome da tabela caso o Laravel tente pluralizar errado
    protected $table = 'inboxes';

    /**
     * Os atributos que podem ser preenchidos em massa (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'tournament_id',
        'title',
        'message',
        'is_read',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Relacionamento: Uma mensagem pertence a um Usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Uma mensagem pode estar vinculada opcionalmente a um Torneio.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}