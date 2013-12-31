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
        'i feel',
        'i wish',
        'i want',
        'i will',
    );

  /**
   * Enqueue each status
   *
   * @param string $status
   */
  public function enqueueStatus($status)
  {
    /*
     * In this simple example, we will just display to STDOUT rather than enqueue.
     * NOTE: You should NOT be processing tweets at this point in a real application, instead they should be being
     *       enqueued and processed asyncronously from the collection process.
     */
    $data = json_decode($status, true);

    // queue status
    Queue::push('App\Queues\QueueTwitterStatus', array('status' => $data));

    // re write this to use registry and factory
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, '');
    $socket->connect('tcp://127.0.0.1:3000');

    $socket->send($status);

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