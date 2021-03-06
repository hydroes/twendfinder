<?php

class FilterTrackConsumer extends OauthPhirehose
{
    /**
     * Enqueue each status
     *
     * @param string $status
     */
    public function enqueueStatus($status)
    {
        // sometimes empty data is passed, not sure lib does this
        if (strlen($status) === 0)
        {
            return;
        }

        $data = json_decode($status);

        // ignore limit messages
        if (isset($data->limit) === true) {
            return;
        }

        // NB: log warnings so that account does not get disconnected
        if (isset($data->warning) === true) {
            $msg = '';
            $code = (isset($data->code) === true) ? $data->code : '';
            $message = (isset($data->message) === true) ? $data->message : '';
            $percent_full =  (isset($data->percent_full) === true) ? (int)$data->percent_full : 0;
            $msg = $code . ': ' . $message;
            \Log::warning('TWITTER QUEUE - ' . $msg);

            // stop processing until queue catches up
            if ($percent_full >= 80) 
            {
                \Log::warning('TWITTER QUEUE OVER 80% capacity');
                return;
            }
        }

        // create zmq socket
        $socket = \App::make('zeroMqSocket');

        // Send status to PHP analytics consumer
        $socket->send("tweets", ZMQ::MODE_SNDMORE);
        $socket->send($status);

        // extract basic tweet to send to nodejs sockets
        $tweet = $this->_buildTweet($data);

        $socket->send("microTweets", ZMQ::MODE_SNDMORE);
        $socket->send($tweet);

  }

  /**
   * ETL for extracting basic tweet information to JSON format.
   *
   * @param  array $data Tweet status
   * @return string
   */
  protected function _buildTweet($data) {
      $tweet = array();
      $tweet['id'] = (isset($data->id) === true) ? $data->id : '';
      $tweet['id_str'] = (isset($data->id_str) === true) ? $data->id_str : '';
      $tweet['text'] = (isset($data->text) === true) ? $data->text : '';

      $tweet['screen_name'] = '';
      $tweet['profile_pic'] = '';

      if (isset($data->user) === true) {
          $tweet['screen_name'] = (isset($data->user->screen_name) === true) ? $data->user->screen_name : '';
          $tweet['profile_pic'] = (isset($data->user->profile_image_url) === true) ? $data->user->profile_image_url : '';
      }

      // get retweet info
      $tweet['retweet_user'] = '';
      if (isset($data->retweeted_status) === true && isset($data->retweeted_status->user) === true)
      {
            $tweet['retweet_user'] =
                (isset($data->retweeted_status->user->screen_name) === true) ? $data->retweeted_status->user->screen_name : '';
      }

      // get url info
      $tweet['urlEntities'] = array();
      if (isset($data->entities) === true && isset($data->entities->urls) === true)
      {
          foreach ($data->entities->urls as $url)
          {
              $tweet['urlEntities'][] = $url;
          }
      }

      return json_encode($tweet);

  }

    /**
    * Retrieves the latest list of words to track.
    * The speed of this method will affect how quickly you can update filters.
    *
    * @return void
    */
    public function checkFilterPredicates()
    {
        /**
        * Specifies keywords to track. Track keywords are case-insensitive logical ORs. Terms are exact-matched, ignoring
        * punctuation. Phrases, keywords with spaces, are not supported. Queries are subject to Track Limitations.
        * Applies to: METHOD_FILTER
        *
        * See: http://apiwiki.twitter.com/Streaming-API-Documentation#TrackLimiting
        *
        * @param array $trackWords
        */
        $this->setTrack(Config::get('twitter.keywords'));
    }
}