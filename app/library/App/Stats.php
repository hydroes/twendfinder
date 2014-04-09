<?php

class Stats
{
    /**
     * Amount of time in minutes to keep data cached for
     *
     * @var integer
     */
    public $cache_expiry = 10080; // 7 days
    
    /**
     * Saves a counter to datastore
     *
     * @param string $key Name of counter increment
     * @param int $increment_amount Amount to increment counter by
     * @param int $expiry Cache expire time in minutes
     * @return void
     */
    public function incrementCounter($key, $increment_amount, $expiry = null)
    {
        // do nothing if nothing to increment
        if ((int)$increment_amount === 0)
        {
            return;
        }

        $expires = (isset($expiry) === true
            && is_int($expiry) === true) ? $expiry : $this->cache_expiry;

        $current_count = Cache::get($key, 0);

        $new_count = $current_count + $increment_amount;

        // count statuses per minute
        if ($current_count === 0)
        {
            Cache::add($key, $new_count, $expires);
        }
        else
        {
            Cache::put($key, $new_count, $expires);
        }
    }
}