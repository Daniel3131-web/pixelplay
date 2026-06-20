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
        'amount',
        'status',      // 'pendente', 'pago'
        'type',        // 'online', 'presencial'
        'checked_in',  // boolean (0 ou 1)
    ];

    /**
     * Casts para garantir que os tipos de dados sejam tratados corretamente.
     */
    protected $casts = [
        'checked_in' => 'boolean',
        'amount'     => 'decimal:2',
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
     * Método auxiliar para verificar se o pedido é presencial.
     */
    public function isPresencial(): bool
    {
        return $this->type === 'presencial';
    }
}