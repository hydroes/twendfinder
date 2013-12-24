<?php

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface
{
    protected $_clients;

    public $subscribedTopics = array();

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "client connected\n";
        $this->clients->attach($conn);
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        echo "onSubscribe\n";
        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        echo "onUnSubscribe\n";
    }
    public function onClose(ConnectionInterface $conn)
    {
        echo "Connection {$conn->resourceId} has disconnected\n";
        $this->clients->detach($conn);

    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        echo "onCall\n";
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        echo 'onPublish';
        echo "\n";
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry)
    {
        echo 'onBlogEntry';
//        $entryData = json_decode($entry, true);

        // If the lookup topic object isn't set there is no one to publish to
//        if (!array_key_exists($entryData['cat'], $this->subscribedTopics)) {
//            return;
//        }

        $topic = $this->subscribedTopics['test'];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast('test');
    }
}