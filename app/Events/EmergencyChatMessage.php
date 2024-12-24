<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmergencyChatMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    public function __construct($message, $user)
    {
        $this->message = $message;
        $this->user = $user;
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

