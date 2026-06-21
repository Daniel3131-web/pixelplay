<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Tournament;
use App\Models\User;

class PaymentService
{
    public function createOrder(User $user, Tournament $tournament): Order
    {
        return Order::create([
            'user_id' => $user->id,
            'tournament_id' => $tournament->id,
            'status' => 'pending',
            'amount' => $tournament->entry_fee ?? 0,
        ]);
    }

    public function processPayment(Order $order): void
    {
        $order->update(['status' => 'processing']);
    }

    public function confirmPayment(Order $order): void
    {
        $order->update(['status' => 'confirmed']);
    }

    public function cancelPayment(Order $order): void
    {
        $order->update(['status' => 'cancelled']);
    }

    public function getOrder(int $orderId): Order
    {
        return Order::findOrFail($orderId);
    }
}
