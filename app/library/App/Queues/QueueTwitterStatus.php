<?php
namespace App\Queues;

class QueueTwitterStatus
{
    public function fire($job, $data)
    {
        var_dump($data);
        // Minify, crop, shrink, apply filters or otherwise manipulate the image
    }
}