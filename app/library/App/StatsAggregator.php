<?php

class StatsAggregator extends Stats
{
    /**
     * Determines the interval in seconds to aggregate stats
     *
     * @var int
     */
    private $_process_interval = 60;

    const MINUTE = 'minute';

    const HOUR = 'hour';

    const DAY = 'day';

    const WEEK = 'week';

    const MONTH = 'month';

    /**
     * This method runs every x amount of seconds and processes aggregate stats
     *
     * @return void
     */
    public function process()
    {
        $time_periods = array(
            'minute' => 60,
            'hour' => 3600,
            'day' => 1440,
        );
        $time = time();
echo "process run {$time}\n";
        foreach ($time_periods as $period_name => $period_time)
        {
            $keyname = "last_{$period_name}_aggregated";
            // only aggregate hourly stats on an hourly basis
            $last_period_aggregated = Cache::get($keyname, 0);

            if (($time - $last_period_aggregated) > $period_time)
            {echo "period {$period_name} expired\n";
                Cache::forget($keyname);
                Cache::put($keyname, $time, $this->cache_expiry);
                $this->_countStatusesForPeriod($period_name);
            }
        }

        sleep($this->_process_interval);
    }

    /**
     * Retrieves & caches count totals for a specified period
     *
     * @param string $period Period to retrieve count totals for
     * @return void
     */
    protected function _countStatusesForPeriod($period = null)
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

            // get totals for time period
            for ($min = 1; $min <= $time_period; $min++)
            {
                $key_prefix = date(
                    'd_m_Y_H_i',
                    strtotime("now - {$min} minute")
                );
echo "{$key_prefix}\n";
                $keyname = "{$key_prefix}_total";
                $last_period_total += Cache::get($keyname, 0);
            }
        }

        $last_period_key = "last_{$period}_total";

        if (Cache::has($last_period_key))
        {
            Cache::forget($last_period_key);
        }

        Cache::add($last_period_key, $last_period_total, $this->cache_expiry);

    }
}