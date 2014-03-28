<?php
/*
|--------------------------------------------------------------------------
| Bind IOC containers
|--------------------------------------------------------------------------
|
| Creates and configures classes that are used globally by the app
|
*/
App::bind('twitterClient', function($app)
{
    $twitterClient = new TwitterClient(
        Config::get('twitter.consumer_key'),
        Config::get('twitter.consumer_secret'),
        Config::get('twitter.access_token'),
        Config::get('twitter.access_token_secret')
    );

    return $twitterClient;
});

App::bind('zeroMqSubscriberSocket', function($app) {
    // create zero mq context object
    $context = new ZMQContext();
    $socket = new ZMQSocket($context, ZMQ::SOCKET_SUB);
    $socket->connect("tcp://localhost:3000");
    $socket->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "tweets");

    return $socket;
});


App::singleton('mongoClient', function()
{
    // Create mongo connection
    $mongoClient = new MongoClient();
//    $db = $mongoClient->selectDB(Config::get('database.mongo_db_name'));
    return $mongoClient;
});

App::singleton('zeroMqSocket', function()
{
    // create zero mq context object
    $context = new ZMQContext();

    // Socket to send messages on
    $socket = new ZMQSocket($context, ZMQ::SOCKET_PUB);

    // bind socket to ip and port
    $socket->bind('tcp://127.0.0.1:3000');

    return $socket;

});

