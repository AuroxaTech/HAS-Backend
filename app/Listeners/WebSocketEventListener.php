<?php

namespace App\Listeners;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\MessageSent;

class WebSocketEventListener
{
    public function handle(MessageSent $event)
    {
        $messageData = [
            'sender_id' => $event->message->sender_id,
            'receiver_id' => $event->message->receiver_id,
            'message' => $event->message->message,
            'type' => $event->message->type
        ];

        // Initialize the WebSocket client
        $client = new Client(new Version2X("http://localhost:8080"));
        $client->initialize();

        // Emit the 'chat' event to the WebSocket server
        $client->emit('chat', $messageData);

        // Close the WebSocket client connection
        $client->close();
    }
}
