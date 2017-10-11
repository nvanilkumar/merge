<?php

require '../vendor/autoload.php';

use Ratchet\Http\OriginCheck;
use Ratchet\Server\IoServer;
use Ratchet\http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(new Httpserver(new WsServer(new Chat())), 2000);

$server->run();