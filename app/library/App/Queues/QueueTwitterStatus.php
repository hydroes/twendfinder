<?php

class QueueTwitterStatus
{
    public function fire($job, $data)
    {
        var_dump($data);

        Log::info($data['text']);

        // NB: be sure to release job else it never leaves
        $job->release();
    }
}