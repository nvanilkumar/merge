<?php

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {

    protected $subscribedTopics = array();
    protected $clients;
    protected $dbh;
    private $subscriptions;
    private $users;
    private $dbuser;
    private $dbpwd;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
        $this->users = [];
        $this->customers = [];
        $this->dbuser = 'root';
        $this->dbpwd = 'admin';
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo "subscribe\n";
 
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry) {
        $entryData = json_decode($entry, true);
        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['campaignId'], $this->subscribedTopics)) {
            return;
        }
        print_r($entryData);
        $topic = $this->subscribedTopics[$entryData['campaignId']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entry);

    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "On open called\n";
    }

    public function onClose(ConnectionInterface $conn) {
        echo "On close called\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        //$conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        echo "On publish called";
        // In this application if clients send data it's because the user hacked around in console
        //$conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "On error called\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "On message called\n";

//        $numRecv = count($this->clients) - 1;
//        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
//                , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
//
//        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//                // The sender is not the receiver, send to each client connected
//                $client->send($msg);
//            }
//        }
    }

}
