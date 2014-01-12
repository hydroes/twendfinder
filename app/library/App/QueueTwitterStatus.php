<?php
namespace App\Queues;

class QueueTwitterStatus
{
    public function fire($job, $data)
    {
        // \Log::info('This is some useful information about job: ' . $job->getJobId());

//        $m = \App::make('mongoClient');
//        $collection = $m->selectCollection(\Config::get('database.mongo_db_name'), 'twitter_statuses');
//        $collection->insert($data['status']);

        // insert stream count for each minute
        $key = date('Y-m-d-H-i');
        if (Cache::has($key))
        {
            Cache::increment($key);
        } else
        {
            Cache::add($key, 1, 1000);
        }

        // NB: be sure to release job else it never leaves
        $job->delete();
    }

}