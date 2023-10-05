<?php

namespace serverTexteur;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;

require dirname(__DIR__) . '/vendor/autoload.php';


class serverTexteur {

    public static function createServer() {

            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new Chat()
                    )
                ),
                9099 
            );

            $server->run();
    }

}

serverTexteur::createServer();