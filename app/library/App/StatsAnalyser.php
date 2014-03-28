<?php

class statsAnalyser
{
    /**
     * Amount of time in minutes to keep data cached for
     *
     * @var integer
     */
    public $cache_expiry = 10080; // 7 days

    /**
     * Analyses statuses against the keywords and increments counters based off
     * the matches.
     *
     * @param array $status Social status to analyse
     * @param array $keywords Keywords to match against the status
     * @return void
     */
    public function count(stdClass $status, array $keywords)
    {
        // create cache prefix key
        $key_prefix = date('d_m_Y_H_i');

        // analyse total statuses
        $ttl_status_key = $key_prefix . "_total";
        $this->incrementCounter($ttl_status_key);

        // count individual keywords in statuses
        foreach ($keywords as $keyword)
        {
            if (stripos($status->text, $keyword) !== false)
            {
                $keyname = $key_prefix . "_{$keyword}";
                $this->incrementCounter($keyname);
            }
        }

    }

    /**
     * Saves a counter to
     * @param string $key Name of counter increment
     * @return void
     */
    public function incrementCounter($key)
    {
        $current_count = Cache::get($key, 0);

        $current_count++;

        // count statuses per minute
        if ($current_count === 0)
        {
            Cache::add($key, $current_count, $this->cache_expiry);
        }
        else
        {
            Cache::put($key, $current_count, $this->cache_expiry);
        }
    }
}