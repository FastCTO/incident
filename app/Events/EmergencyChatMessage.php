<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EmergencyChatMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $username;

    public function __construct($message, ?User $user)
    {
        $this->message = $message;
        $this->username = $user ? $user->name : 'Guest';
    }

    public function broadcastOn()
    {
        return new Channel('emergency-chat');
    }

    public function broadcastAs()
    {
        return 'chat-message';
    }
}

