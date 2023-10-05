<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface as WebSocketMessageComponentInterface;


class Chat implements MessageComponentInterface, WebSocketMessageComponentInterface {

 protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage();
        $identifier = uniqid();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nouvelle connexion WebSocket\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        $combinedData = array(
            'message' => $data
        );
    
        $combinedMsg = json_encode($combinedData);

        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send($combinedMsg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connexion WebSocket fermée\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Erreur WebSocket : {$e->getMessage()}\n";
        $conn->close();
    }

}
