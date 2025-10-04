<?php
// Include the necessary Ratchet WebSocket components
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require dirname(__DIR__) . '/vendor/autoload.php';

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
        
        // Handle different types of messages
        switch ($data->type) {
            case 'text':
                $this->sendTextMessage($from, $data);
                break;
            case 'video':
            case 'image':
            case 'audio':
            case 'document':
                $this->handleFileUpload($from, $data, $data->type);
                break;
            case 'location':
                $this->handleLocation($from, $data);
                break;
            case 'contact':
                $this->handleContact($from, $data);
                break;
            case 'poll':
                $this->handlePoll($from, $data);
                break;
            default:
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Remove the connection
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    // Handle text messages
    private function sendTextMessage(ConnectionInterface $from, $data) {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $data->status = 'delivered';
                $client->send(json_encode($data));
            } else {
                $data->status = 'sent';
                $from->send(json_encode($data));
            }
        }
    }

    // Handle file uploads (video, image, audio, document)
    private function handleFileUpload(ConnectionInterface $from, $data, $type) {
        // Process file upload (e.g., save to server)
        // Send a confirmation message to clients
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $data->status = 'delivered';
                $client->send(json_encode($data));
            } else {
                $data->status = 'sent';
                $from->send(json_encode($data));
            }
        }
    }

    // Handle location sharing
    private function handleLocation(ConnectionInterface $from, $data) {
        // Process location sharing
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $data->status = 'delivered';
                $client->send(json_encode($data));
            } else {
                $data->status = 'sent';
                $from->send(json_encode($data));
            }
        }
    }

    // Handle contact sharing
    private function handleContact(ConnectionInterface $from, $data) {
        // Process contact sharing
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $data->status = 'delivered';
                $client->send(json_encode($data));
            } else {
                $data->status = 'sent';
                $from->send(json_encode($data));
            }
        }
    }

    // Handle polls
    private function handlePoll(ConnectionInterface $from, $data) {
        // Process poll creation
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $data->status = 'delivered';
                $client->send(json_encode($data));
            } else {
                $data->status = 'sent';
                $from->send(json_encode($data));
            }
        }
    }
}

$server = Ratchet\App::factory('localhost', 8080);
$server->route('/chat', new Chat, ['*']);
$server->run();
?>
