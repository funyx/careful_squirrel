<?php

namespace App\Broadcasting;

use App\Models\User;

class PrivateMessageChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join( User $user, int $recipientId ): bool
    {
        return (int)$user->id === $recipientId;
    }
}
