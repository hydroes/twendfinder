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
    
    protected $_status_count = 0;

  /**
   * Enqueue each status
   *
   * @param string $status
   */
    public function enqueueStatus($status)
    {
        // TODO: sometimes empty data is passed, not sure lib does this
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
            if ($percent_full >= 80) {
                return;
            }
        }
        
        // count the status as a tweet
        ++$this->_status_count;
        
        // Send status to PHP analytics consumer
//        $socket = \App::make('zeroMqSocketPhp');
//        $socket->send($status);
        
        // queue status
        //    Queue::push('App\Queues\QueueTwitterStatus', array('status' => $data));

        // create zmq socket
        $nodeSocket = \App::make('zeroMqSocketNode');

        // build basic tweet to send to nodejs sockets
        $tweet = $this->_buildTweet($data);
        $nodeSocket->send($tweet);
  }

  /**
   * ETL for extracting basic tweet information.
   * 
   * @param  array $data Tweet status
   * @return string
   */
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
        $this->setTrack($this->myTrackWords);
    }
}