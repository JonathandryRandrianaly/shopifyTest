<?php

namespace App\Service;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MessageHandler implements MessageComponentInterface {

    protected $connections;

    public function __construct(){        
        $this->connections = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
    }
 
    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach($this->connections as $connection)
        {
            if($connection === $from)
            {
                continue;
            }
            $connection->send($msg);
        }
    }
 
    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
    }
 
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->connections->detach($conn);
        $conn->close();
    }

    public function notifyFileReady(string $fileUrl)
    {
        foreach($this->connections as $connection)
        {
            $connection->send(json_encode(['fileReady' => $fileUrl]));
        }
    }
}