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
            $keyname = $key_prefix . "_{$keyword}";
            $keyword_count = 0;
            $this->_findKeywords($status->text, $keyword, $keyword_count);

            $this->incrementCounter($keyname, $keyword_count);

        }

    }

    /**
     * Recursivly iterates through a string and returns how many times a keyword
     * occurs in the string.
     *
     * @param string $string String to search keywords
     * @param string $keyword Keyword to look for in string
     * @param int $offset Last offset where keyword was found in string
     * @param int $keyword_count Number of keywords found
     * @return integer
     */
    protected function _findKeywords($string, $keyword, &$keyword_count, $offset = 0)
    {
        if (stripos($string, $keyword, $offset) !== false)
        {
            $keyword_count++;

            $offset = (int)$offset + strlen($keyword);

            $this->_findKeywords($string, $keyword, $offset, $keyword_count);
        }

        return $keyword_count;
    }

    /**
     * Saves a counter to
     * @param string $key Name of counter increment
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