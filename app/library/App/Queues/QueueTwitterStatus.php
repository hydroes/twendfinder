<?php
namespace App\Queues;

class QueueTwitterStatus
{
    public function fire($job, $data)
    {

//        var_dump('Job ID: '.$job->getJobId());
//        var_dump($data);
        sleep(120);
        // NB: be sure to release job else it never leaves
        $job->delete();
    }
}