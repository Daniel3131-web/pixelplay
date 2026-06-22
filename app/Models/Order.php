<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos via atribuição em massa.
     */
    protected $fillable = [
        'user_id',
        'tournament_id',
        'event_id',
        'amount',
        'status',
        'type',
        'metodo',             // Adicionado (exatamente como está no BD)
        'is_team_payment',    // Adicionado (Essencial para a lógica do time)
        'checked_in',
    ];

    /**
     * Casts para garantir que os tipos de dados sejam tratados corretamente.
     */
    protected $casts = [
        'checked_in' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Relacionamento: Um pedido pertence a um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Um pedido pertence a um torneio.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Relacionamento: Um pedido pertence a um evento.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Método auxiliar para verificar se o pedido é presencial.
     */
    public function isPresencial(): bool
    {
        return $this->type === 'presencial';
    }
}