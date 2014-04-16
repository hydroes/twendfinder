<?php

class StatsAggregator extends Stats
{
    /**
     * Determines the interval in seconds to aggregate stats
     *
     * @var int
     */
    private $_process_interval = 29;

    const MINUTE = 'minute';

    const HOUR = 'hour';

    const DAY = 'day';

    const WEEK = 'week';

    const MONTH = 'month';

    private $_aggregate_periods = array(
        self::MINUTE => 60,
        self::HOUR => 3600,
        self::DAY => 86400,
    );

    /**
     * This method runs every x amount of seconds and processes aggregate stats
     *
     * @return void
     */
    public function process()
    {
        $time = time();

        $this->_countStatsForLastPeriodX($time);

        sleep($this->_process_interval);
    }

    /**
     * Counts and caches statuses recieved in the last minute, hour, day etc.
     *
     * @param int $time
     */
    protected function _countStatsForLastPeriodX($time)
    {
        foreach ($this->_aggregate_periods as $period_name => $period_time)
        {
            $keyname = "last_{$period_name}_aggregated";
            $last_period_aggregated = Cache::get($keyname, 0);

            // only aggregate hourly stats every x period
            if (($time - $last_period_aggregated) > $period_time)
            {
                Cache::forget($keyname);
                Cache::put($keyname, $time, $this->cache_expiry);
                $last_period_total =
                    $this->_getCounterTotalByPeriodAndName($period_name,
                    'total');

                $last_period_key = "last_{$period_name}_total";

                Cache::forget($last_period_key);

                Cache::add($last_period_key, $last_period_total,
                    $this->cache_expiry);

            }
        }
    }

    /**
     * Retrieves count totals for a specified period
     *
     * @param string $period Period to retrieve count totals for
     * @param string $count_type Counter type to retrieve
     * @return int
     */
    protected function _getCounterTotalByPeriodAndName($period = null,
        $count_type = '')
    {
        $last_period_total = 0;
        $time_period = 0;

        switch ($period)
        {
            case self::MINUTE :
                $time_period = 1;
                break;
            case self::HOUR :
                $time_period = 60;
                break;
            case self::DAY :
                $time_period = 1440;
                break;
            case self::WEEK :
                $time_period = 10080;
                break;
            default:
                \Log::error('Valid period not given');
                return;
        }

        if (empty($count_type) === true)
        {
            \Log::error('Valid count type not given');
            return;
        }

        // get totals for time period
        for ($min = 1; $min <= $time_period; $min++)
        {
            $key_prefix = date(
                self::KEY_FORMAT,
                strtotime("now - {$min} minute")
            );

            $keyname = "{$key_prefix}_{$count_type}";
            $last_period_total += Cache::get($keyname, 0);
        }

        return $last_period_total;

    }
}