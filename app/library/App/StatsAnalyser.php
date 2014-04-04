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
        $this->incrementCounter($ttl_status_key, 1);

        // count individual keywords in statuses
        foreach ($keywords as $keyword)
        {
            $keyname = $key_prefix . "_{$keyword}";
            $keyword_count = $this->_findKeywords($status->text, $keyword);

            $this->incrementCounter($keyname, $keyword_count);

            // keyword counts can be tallied up and used to check keyword totals
            if ($keyword_count === 1)
            {
                $this->incrementCounter("{$key_prefix}_single", 1);
            }
            else if ($keyword_count > 1)
            {
                // count number of statuses with multi keywords
                $this->incrementCounter("{$key_prefix}_multi", 1);
                // count the total number of keywords in multi key statuses
                $this->incrementCounter("{$key_prefix}_multi_num", $keyword_count);
            }

        }

    }

    /**
     * Iterates through a string and returns how many times a keyword
     * occurs in the string.
     *
     * @param string $string String to search keywords
     * @param string $keyword Keyword to look for in string
     * @return integer
     */
    protected function _findKeywords($string, $keyword)
    {
        $c = 0;
        $keyword_count = 0;
        // Calculate the length once for all
        $keyword_length = strlen($keyword);

        while(($i = stripos($string, $keyword, $c)) !== false)
        {
            $c = $i + $keyword_length;
            $keyword_count++;
        }

        return $keyword_count;
    }

    /**
     * Saves a counter to datastore
     * 
     * @param string $key Name of counter increment
     * @param int $increment_amount Amount to increment counter by
     * @return void
     */
    public function incrementCounter($key, $increment_amount = 0)
    {
        // do nothing if nothing to increment
        if ((int)$increment_amount === 0)
        {
            return;
        }

        $current_count = Cache::get($key, 0);

        $new_count = $current_count + $increment_amount;

        // count statuses per minute
        if ($current_count === 0)
        {
            Cache::add($key, $new_count, $this->cache_expiry);
        }
        else
        {
            Cache::put($key, $new_count, $this->cache_expiry);
        }
    }
}