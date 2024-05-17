<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use React\EventLoop\Factory;
use React\Socket\Server as Reactor;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;
use App\Http\Controllers\WebSocketController;
class WebSocketServer extends Command
{
    protected $signature = 'websocket:serve';
    protected $description = 'Start the WebSocket server';

    public function handle()
    {
        $loop = Factory::create();
        $webSocketServer = new Reactor('127.0.0.1:8080', $loop);
        $wsServer = new WsServer(new WebSocketController); // Adjust the WebSocket controller namespace if needed
        $httpServer = new HttpServer($wsServer);
    
        $ioServer = new IoServer($httpServer, $webSocketServer);
    
        $this->info('WebSocket server started now');
        $loop->run();
    }
}
