<?php

class statsCounter
{
    /**
     * Amount of time in minutes to keep data cached for
     *
     * @var integer
     */
    public $cache_expiry = 10080;

    /**
     * Analyses statuses against the keywords and increments counters based off
     * the matches.
     *
     * @param array $status Social status to analyse
     * @param array $keywords Keywords to match against the status
     * @return void
     */
    public function analyse(array $status, array $keywords)
    {
        // analyse total statuses

        // count individual keywords in statuses

        foreach ($keywords as $keyword)
        {

        }

    }
}