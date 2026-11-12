<?php

namespace App\Policies;

use App\Models\ShiftSession;
use App\Models\User;

class ShiftSessionPolicy
{
    public function view(User $user, ShiftSession $session): bool
    {
        if ((int) $session->user_id === (int) $user->id) {
            return true;
        }
        return $user->hasPermission('shift.manage') && $user->canAccessStore($session->store_id);
    }

    /** Chá»‰ chá»§ phiÃªn (hoáº·c quáº£n lÃ½ trong pháº¡m vi) Ä‘Æ°á»£c thao tÃ¡c tiá»n / Ä‘Ã³ng ca */
    public function operate(User $user, ShiftSession $session): bool
    {
        if (!$session->isOpen()) {
            return false;
        }
        if ((int) $session->user_id === (int) $user->id) {
            return true;
        }
        return $user->hasPermission('shift.manage') && $user->canAccessStore($session->store_id);
    }
}
