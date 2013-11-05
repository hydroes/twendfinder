<?php

class FilterTrackConsumer extends OauthPhirehose
{
   /**
   * Subclass specific attribs
   */
//  protected $myTrackWords = array('morning', 'goodnight', 'hello', 'the');

    protected $myTrackWords = array('honeymoon', 'billionare', 'sex', 'brian gouws');

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

      // TODO: save statuses to mongo (use status ids as _ids for speed)


//      echo 'status';
//      var_dump($status);
    $data = json_decode($status, true);

    // queue status
    Queue::push('App\Queues\QueueTwitterStatus', $data);

//    var_dump($data);
//    if (is_array($data) && isset($data['user']['screen_name'])) {
//      echo $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
//    }
//		exit;
  }

  /**
   * In this example, we just set the track words to a random 2 words. In a real example, you'd want to check some sort
   * of shared medium (ie: memcache, DB, filesystem) to determine if the filter has changed and set appropriately. The
   * speed of this method will affect how quickly you can update filters.
   */
  public function checkFilterPredicates()
  {
    // This is all that's required, Phirehose will detect the change and reconnect as soon as possible
    $randWord1 = $this->myTrackWords[rand(0, 3)];
    $randWord2 = $this->myTrackWords[rand(0, 3)];
    $this->setTrack(array($randWord1, $randWord2));
  }

}