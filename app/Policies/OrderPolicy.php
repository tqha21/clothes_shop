<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /** Xem giao dá»‹ch POS: trong pháº¡m vi cá»­a hÃ ng; staff chá»‰ xem Ä‘Æ¡n cá»§a chÃ­nh mÃ¬nh hoáº·c cÃ¹ng cá»­a hÃ ng */
    public function viewPos(User $user, Order $order): bool
    {
        if (!$user->hasPermission('pos.sell') || !$order->isPos()) {
            return false;
        }
        return $user->canAccessStore($order->store_id);
    }

    public function refund(User $user, Order $order): bool
    {
        return $user->hasPermission('pos.refund')
            && $order->isPos()
            && $user->canAccessStore($order->store_id)
            && in_array($order->status, ['completed'], true)
            && $order->payment_status !== 'refunded';
    }

    /** XÃ¡c nháº­n / há»§y thanh toÃ¡n Ä‘ang chá» (chuyá»ƒn khoáº£n) */
    public function resolvePayment(User $user, Order $order): bool
    {
        if (!$order->isPos() || $order->payment_status !== 'pending') {
            return false;
        }
        if (!$user->canAccessStore($order->store_id)) {
            return false;
        }
        return (int) $order->staff_id === (int) $user->id || $user->hasPermission('pos.void');
    }
}
