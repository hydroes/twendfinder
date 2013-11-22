<?php
namespace App\Queues;

use Illuminate;

class QueueTwitterStatus
{
    public function fire($job, $data)
    {

//        var_dump('Job ID: '.$job->getJobId());
//        var_dump($data);
        Log::info('This is some useful information about job: ' . $job->getJobId());
//        sleep(60);
        // NB: be sure to release job else it never leaves
        $job->delete();
    }

    function namespaceExists($namespace)
    {
        $namespaces=array();
        foreach(get_declared_classes() as $name) {
            if(preg_match_all("@[^\\\]+(?=\\\)@iU", $name, $matches)) {
                $matches = $matches[0];
                $parent =&$namespaces;
                while(count($matches)) {
                    $match = array_shift($matches);
                    if(!isset($parent[$match]) && count($matches))
                        $parent[$match] = array();
                    $parent =&$parent[$match];

                }
            }
        }
    }

}