<?php

class StatsAggregator extends Stats
{
    /**
     * Determines the interval in seconds to aggregate stats
     *
     * @var int
     */
    private $_process_interval = 60;

    /**
     * Number of times that the process method is run
     *
     * @var int
     */
    private $_processed_count = 0;

    /**
     * This method runs every x amount of seconds and processes aggregate stats
     *
     * @return void
     */
    public function process()
    {
        $this->_countStatusesPerMinute();

        sleep($this->_process_interval);
        $this->_processed_count++;
    }

    /**
     * Updates the cache with the total number of statuses counted in the last
     * minute.
     *
     * @return void
     */
    protected function _countStatusesPerMinute()
    {
        // create key of last minute and get total
        $key_prefix = date('d_m_Y_H_i', strtotime("now - 1 minute"));
        $key_name = "{$key_prefix}_total";
        $last_minute_total = Cache::get($key_name);

        $last_minute_key = 'statuses_last_minute';

        // upsert a key that will always hold the number of statuses for the last minute
        if (Cache::has($last_minute_key))
        {
            Cache::forget($last_minute_key);
        }

        Cache::add($last_minute_key, $last_minute_total, $this->cache_expiry);
    }
}