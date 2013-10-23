<?php

class ApiController extends \BaseController {

    public function getIndex()
    {
        // get search term and return tweets
        $twitterClient = App::make('twitterClient');

        // tweets
        $search = array(
            'q' => $_GET['q'],
            'count' => 100,
        );
        $tweets = $twitterClient->get('search/tweets.json', $search);

        $reputes = array();

        foreach ($tweets['statuses'] as $tweet) {
            $reputes[] = $tweet['text'];
        }

//        print_r($tweets);

        return Response::json($reputes);
    }

    public function postProfile()
    {
        //
    }

}