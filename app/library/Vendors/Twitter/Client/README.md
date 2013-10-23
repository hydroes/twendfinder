TwitterClient
=============

A simple Twitter 1.1 API consumer that does not require `cURL`.

### Example:

	<?php
	$twitter = new TwitterClient($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	$tweets = $twitter->get('statuses/user_timeline.json', array('screen_name'=>'twitterapi', 'count'=>100));
	
This will give you back an associative array [as described here](https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline).

Get your keys [here](https://dev.twitter.com/apps).