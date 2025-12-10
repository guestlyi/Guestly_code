<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;
    public $type;

    public function __construct($message, $userId, $type)
    {
        $this->message = $message;
        $this->userId = $userId;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        if ($this->type == 'user') {
            return ['user-notification.' . $this->userId];
        } elseif ($this->type == 'affiliate') {
            return ['affiliate-notification.' . $this->userId];
        }
    }
}
