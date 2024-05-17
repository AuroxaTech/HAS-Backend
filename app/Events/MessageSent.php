<?php
namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;


class MessageSent implements ShouldBroadcastNow
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chat'); // Define the channel name for broadcasting
    }
}
