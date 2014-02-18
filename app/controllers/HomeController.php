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
            return View::make('demo.page');
	}

        public function showSnapShot()
        {
            $twitterClient = App::make('twitterClient');

            // tweets
            $search = array('q' => $_POST['search_term']);
            $tweets = $twitterClient->get('trends/available.json');

            die('<pre>'.var_dump($tweets));
        }

}