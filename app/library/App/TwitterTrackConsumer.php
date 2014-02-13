<?php

class FilterTrackConsumer extends OauthPhirehose
{
   /**
   * Subclass specific attribs
   */
    protected $myTrackWords = array(
        'i love',
        'i hate',
        'i think',
        'i believe',
        'i want',
    );

  /**
   * Enqueue each status
   *
   * @param string $status
   */
    public function enqueueStatus($status)
    {
        // TODO: sometimes empty data is passed, not sure what type it is
        if (is_null($status) === true || empty($status) === true || strlen($status) === 0)
        {
            return;
        }
    /*
     * In this simple example, we will just display to STDOUT rather than enqueue.
     * NOTE: You should NOT be processing tweets at this point in a real application, instead they should be being
     *       enqueued and processed asyncronously from the collection process.
     */
    $data = json_decode($status);

    // queue status
//    Queue::push('App\Queues\QueueTwitterStatus', array('status' => $data));

    // NB: log warnings so that account does not get disconnected
    if (isset($data->warning) === true) {
        $msg = '';
        $code = (isset($data->code) === true) ? $data->code : '';
        $message = (isset($data->message) === true) ? $data->message : '';
        $percent_full =  (isset($data->percent_full) === true) ? (int)$data->percent_full : 0;
        $msg = $code . ': ' . $message;
        \Log::warning('TWIITER QUEUE - ' . $msg);

        // stop processing untill queue catches up
        if ($percent_full >= 80) {
            return;
        }
    }

    // create zmq socket
    $socket = \App::make('zeroMqSocket');

    // build basic tweet to send to nodejs sockets
    $tweet = $this->_buildTweet($data);
    $socket->send($tweet);
  }

  protected function _buildTweet($data) {
      $tweet = array();
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
   * In this example, we just set the track words to a random 2 words. In a real example, you'd want to check some sort
   * of shared medium (ie: memcache, DB, filesystem) to determine if the filter has changed and set appropriately. The
   * speed of this method will affect how quickly you can update filters.
   */
  public function checkFilterPredicates()
  {
    // This is all that's required, Phirehose will detect the change and reconnect as soon as possible
//    $randWord1 = $this->myTrackWords[rand(0, 3)];
//    $randWord2 = $this->myTrackWords[rand(0, 3)];

  /**
   * Specifies keywords to track. Track keywords are case-insensitive logical ORs. Terms are exact-matched, ignoring
   * punctuation. Phrases, keywords with spaces, are not supported. Queries are subject to Track Limitations.
   * Applies to: METHOD_FILTER
   *
   * See: http://apiwiki.twitter.com/Streaming-API-Documentation#TrackLimiting
   *
   * @param array $trackWords
   */
    $this->setTrack($this->myTrackWords);
  }

}