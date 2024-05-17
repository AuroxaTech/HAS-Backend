<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Image;
use Illuminate\Support\Facades\File;
use App\Models\Message;

class WebSocketController implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    // public function onMessage(ConnectionInterface $from, $msg)
    // {
    //     echo "Received message ";
    //     foreach ($this->clients as $client) {
    //         if ($from !== $client) {
    //             $client->send($msg);
    //         }
    //     }
    // }

   
    // Modify the method signature to accept a Request object
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $messageData = json_decode($msg, true);
        // echo $messageData['sender_id'];
        // exit;

        // $messageData = json_decode($msg, true);
        $type = $messageData['type'];
        $sender_id = $messageData['sender_id'];
        $receiver_id = $messageData['receiver_id'];

        if ($type == 1) {
            // Access the file from the request
            $message = $request->file('message');

            if (!$message) {
                return $from->send(json_encode([
                    'status' => false,
                    'message' => 'File is required for type 1 message'
                ]));
            }

            $messageimage = 'File-' . uniqid() . '-' .$message->getClientOriginalName();
            $filePath = public_path('/assets/chat');
            if(!File::isDirectory($filePath)){
                File::makeDirectory($filePath, 0777, true, true);
            }
            $messageimg = Image::make($message->getRealPath());
            $messageimg->save($filePath.'/'.$messageimage);
            $message = $messageimage;

        } elseif ($type == 0) {
            $message = $messageData['message'];
        }

        // Create the message record in the database
        $newMessage = Message::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'type' => $type
        ]);
        // echo 'hh';
        // $messageArray = $newMessage->toArray();
        // echo $messageArray;
        // Broadcast the message data to all connected clients
        foreach ($this->clients as $client) {
            $client->send($newMessage);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
