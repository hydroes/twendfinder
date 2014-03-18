<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class StatusAnalyserCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'status:analyser';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Analyses statuses (tweets) and records relevent stats';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
            // create zmq socket
            $socket = \App::make('zeroMqSubscriberSocket');
            
            $status_count = 0;
            
            while (true) 
            {
                // Get message details
                $address = $socket->recv();
                $contents = $socket->recv();
                
                $data = json_decode($contents);
//                printf ("[%s] %s%s", $address, $contents, PHP_EOL);
                
                // create cache key
                $key = date('d_m_Y_H_i');
                
                $current_count = Cache::get($key, 0);
                $current_count++;
                
                $minutes = 4320; // 3 days
                
                // count statuses per minute
                if ($current_count === 0)
                {
                    Cache::add($key, $current_count, $minutes);
                } 
                else 
                {
                    Cache::put($key, $current_count, $minutes);
                }
                
                $status_count++;
            }
            
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
