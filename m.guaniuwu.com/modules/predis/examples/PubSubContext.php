<?php
require_once 'SharedConfigurations.php';

// Redis 2.0 features new commands that allow clients to subscribe for 
// events published on certain channels (PUBSUB).

// Create a client and disable r/w timeout on the socket
$redis  = new Predis\Client($single_server + array('read_write_timeout' => 0));

// Initialize a new pubsub context
$pubsub = $redis->pubSubContext();

// Subscribe to your channels
$pubsub->subscribe('control_channel');
$pubsub->subscribe('notifications');

// Start processing the pubsup messages. Open a terminal and use redis-cli 
// to push messages to the channels. Examples:
//   ./redis-cli PUBLISH notifications "this is a test"
//   ./redis-cli PUBLISH control_channel quit_loop
foreach ($pubsub as $message) {
    switch ($message->kind) {
        case 'subscribe':
            echo "Subscribed to {$message->channel}\n";
            break;
        case 'message':
            if ($message->channel == 'control_channel') {
                if ($message->payload == 'quit_loop') {
                    echo "Aborting pubsub loop...\n";
                    $pubsub->unsubscribe();
                }
                else {
                    echo "Received an unrecognized command: {$message->payload}.\n";
                }
            }
            else {
                echo "Received the following message from {$message->channel}:\n",
                     "  {$message->payload}\n\n";
            }
            break;
    }
}

// Always unset the pubsub context instance when you are done! The 
// class destructor will take care of cleanups and prevent protocol 
// desynchronizations between the client and the server.
unset($pubsub);

// Say goodbye :-)
$info = $redis->info();
print_r("Goodbye from Redis v{$info['redis_version']}!\n");
?>
