<?php
namespace App\Queues;

class QueueTwitterStatus
{
    public function fire($job, $data)
    {

//        var_dump('Job ID: '.$job->getJobId());
//        var_dump($data);
        \Illuminate\Log::info('This is some useful information about job: ' . $job->getJobId());
//        sleep(60);
        // NB: be sure to release job else it never leaves
        $job->delete();
    }
}