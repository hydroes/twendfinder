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
     * This method runs every x amount of seconds and processes aggregate stats
     *
     * @return void
     */
    public function process()
    {
        echo "\nprocessing " . time();
        sleep($this->_process_interval);
    }
}