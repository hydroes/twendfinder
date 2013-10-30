<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
            $q = new App\Queues\QueueTwitterStatus();
            return View::make('demo.page');
	}

        public function showSnapShot()
        {
            $twitterClient = App::make('twitterClient');

            // tweets
            $search = array('q' => $_POST['search_term']);
            $tweets = $twitterClient->get('search/tweets.json', $search);

            die('<pre>'.var_dump($tweets));
        }

}