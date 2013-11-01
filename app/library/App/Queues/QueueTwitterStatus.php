<?php
namespace App\Queues;

class QueueTwitterStatus
{
    public function fire($job, $data)
    {
        var_dump($data);

		// NB: be sure to release job else it never leaves
		$job->release();
    }
}